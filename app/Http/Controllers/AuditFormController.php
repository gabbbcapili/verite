<?php

namespace App\Http\Controllers;

use App\Models\AuditForm;
use App\Models\Template;
use App\Models\AuditFormHeader;
use Illuminate\Http\Request;
use App\Models\Question;
use Validator;
use Carbon\Carbon;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AuditReview;
use App\Mail\Audit\ReviewNotification;
use App\Mail\Audit\ReviewResolved;
use Illuminate\Support\Facades\Mail;

class AuditFormController extends Controller
{
    public function index()
    {
        //
    }

    public function create(AuditForm $auditForm, $template = null)
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('audit.index'), 'name'=>"Audits"], ['link'=> route('audit.show', $auditForm->audit),'name'=> $auditForm->audit->schedule->title], ['name' => $auditForm->template->name]
        ];

        return view('app.audit.auditForm.create', compact('auditForm', 'breadcrumbs'));
    }

    public function store(Request $request, AuditForm $auditForm)
    {
        $validation = [];
        if(! $request->has('save_finish_later')){
            $validation = Question::getValidation($auditForm);
        }
        $validation['formName'] = 'required';
        $validator = Validator::make($request->all(),
            $validation, Question::getValidationMessages());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'msg' => 'Please check all errors']);
        }


        try {
            DB::beginTransaction();

            $groupCompleted = 0;
            $formHeaderStatus = 'open';
            foreach($auditForm->template->groups as $group){
                $groupValidation = Question::getValidation($group, true);
                $validator = Validator::make($request->all(),
                $groupValidation, Question::getValidationMessages());
                if(! $validator->fails()){
                    $groupCompleted += 1;
                }
            }
            
            if($groupCompleted > 0){
                if($request->has('approveForm')){
                    $formHeaderStatus = 'approved';
                } else if(! $request->has('save_finish_later') && ! $request->has('approveForm')){
                    $formHeaderStatus = 'submitted';
                }else{
                    $formHeaderStatus = 'partial';
                }
            }

            $requestFormHeaders = [
                'name' => $request->formName,
                'status' => $formHeaderStatus,
                'groupCompleted' => $groupCompleted,
            ];

            $auditFormHeader = $auditForm->headers()->create($requestFormHeaders);

            Question::processAnswers($request, $auditFormHeader, 'uploads/spaf/');
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Form answered successfully!',
                        'redirect' => route('audit.show', $auditForm->audit)
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }

    public function show(AuditFormHeader $auditFormHeader)
    {
        $auditForm = $auditFormHeader->form;
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('audit.index'), 'name'=>"Audits"], ['link'=> route('audit.show', $auditForm->audit),'name'=> $auditForm->audit->schedule->title], ['name' => $auditForm->template->name . ' - ' . $auditFormHeader->name]
        ];
        return view('app.audit.auditForm.show', compact('auditFormHeader', 'breadcrumbs', 'auditForm'));
    }

    public function edit(AuditFormHeader $auditFormHeader, $template = null)
    {
        $auditForm = $auditFormHeader->form;
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('audit.index'), 'name'=>"Audits"], ['link'=> route('audit.show', $auditForm->audit),'name'=> $auditForm->audit->schedule->title], ['name' => $auditForm->template->name . ' - Edit ' . $auditFormHeader->name]
        ];
        return view('app.audit.auditForm.edit', compact('auditFormHeader', 'breadcrumbs', 'auditForm'));
    }

    public function update(Request $request, AuditFormHeader $auditFormHeader)
    {
        $validation = [];
        $auditForm = $auditFormHeader->form;
        if(! $request->has('save_finish_later')){
            $validation = Question::getValidation($auditForm);

        }
        $validator = Validator::make($request->all(),
            $validation, Question::getValidationMessages());
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'msg' => 'Please check all errors']);
        }
        try {
            DB::beginTransaction();

            $groupCompleted = 0;
            $formHeaderStatus = 'open';
            foreach($auditForm->template->groups as $group){
                $groupValidation = Question::getValidation($group, true);
                $validator = Validator::make($request->all(),
                $groupValidation, Question::getValidationMessages());
                if(! $validator->fails()){
                    $groupCompleted += 1;
                }
            }
            
            if($groupCompleted > 0){
                if($request->has('approveForm')){
                    $formHeaderStatus = 'approved';
                } else if(! $request->has('save_finish_later') && ! $request->has('approveForm')){
                    $formHeaderStatus = 'submitted';
                }else{
                    $formHeaderStatus = 'partial';
                }
            }

            $requestFormHeaders = [
                'name' => $request->formName,
                'status' => $formHeaderStatus,
                'groupCompleted' => $groupCompleted,
            ];

            $auditFormHeader->update($requestFormHeaders);

            Question::processAnswers($request, $auditFormHeader, 'uploads/spaf/');
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Form answered successfully!',
                        'redirect' => route('audit.show', $auditForm->audit)
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }

    public function destroy(AuditFormHeader $auditFormHeader)
    {
        try {
            DB::beginTransaction();
            $removeRow = 'header'.$auditFormHeader->id;
            $auditFormHeader->delete();
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit form answer successfully deleted!',
                        'removeRow' => $removeRow
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }

    public function cachedForms(){
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('audit.index'), 'name'=>"Audits"], ['name' => 'Cached Forms']
        ];
        return view('app.audit.auditForm.cachedForms', compact('breadcrumbs'));
    }

    public function approve(AuditFormHeader $auditFormHeader, Request $request){
        try {
            DB::beginTransaction();
            $data = [];
            if($request->has('approve')){
                if(! $request->approve){
                    $data['status'] = 'additional';
                }else{
                    $data['status'] = 'completed';
                }
            }
            $auditFormHeader->update($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Form successfully updated!',
                        'redirect' => route('audit.show', $auditFormHeader->form->audit)
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }

    public function createReview(AuditFormHeader $auditFormHeader){
        $form = $auditFormHeader->form;

        $targets = $form->template->groups;
        return view('app.audit.auditForm.review.create', compact('auditFormHeader', 'targets'));
    }

    public function storeReview(AuditFormHeader $auditFormHeader, Request $request){
        $validator = Validator::make($request->all(),
            [
                'group_id' => 'required',
                'message' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'msg' => 'Please check all errors']);
        }
        try {
            DB::beginTransaction();
            $review = $auditFormHeader->reviews()->create([
                'status' => 'Pending',
                'group_id' => $request->group_id,
                'message' => $request->message
            ]);

            Mail::to($auditFormHeader->created_by_user)->send(new ReviewNotification($review));
            
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Review created successfully!',
                        'table_id' => 'audit_form_reviews',
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }

    public function indexReview(Request $request, AuditFormHeader $auditFormHeader){
        if (request()->ajax()) {
            $reviews = AuditReview::where('audit_form_header_id', $auditFormHeader->id);
            return Datatables::eloquent($reviews)
            ->addColumn('action', function(AuditReview $review) use ($request, $auditFormHeader) {
                if($request->user()->can('auditForm.review')){

                }else{

                }
                if($review->status == 'Pending'){
                    return Utilities::actionButtons([['route' => route('auditForm.review.resolve', $review), 'name' => 'Approve', 'type' => 'confirm', 'title' => 'Are you sure to mark this as resolved?', 'text' => 'Mark as Resolved']]);
                }
            })
            ->addColumn('groupDisplay', function(AuditReview $review) {
                return $review->group ? $review->group->header : '';
            })
            ->addColumn('statusDisplay', function(AuditReview $review){
                return '<span class="text-'. $review->getStatusClass() .'">'. $review->status .'</span>';
            })
            ->editColumn('updated_at', function (AuditReview $review) {
                return $review->updated_at->diffForHumans() . ' | ' . $review->updatedByName;
            })
            ->editColumn('created_at', function (AuditReview $review) {
                return $review->created_at->format('M d, Y') . ' | ' . $review->createdByName;
            })
            ->rawColumns(['action', 'statusDisplay'])
            ->make(true);
        }
    }

    public function resolveReview(AuditReview $auditReview, Request $request){
        try {
            DB::beginTransaction();
            $auditReview->update(['status' => 'Resolved']);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Review marked as resolved successfully!',
                        'table_id' => 'audit_form_reviews',
                    ];

            if($auditReview->created_by_user->id != $request->user()->id){
                Mail::to($auditReview->created_by_user)->send(new ReviewResolved($auditReview));
            }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
             DB::rollBack();
        }
        return response()->json($output);
    }
}

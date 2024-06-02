<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Audit;
use App\Models\Schedule;
use App\Models\Template;
use App\Models\Company;
use App\Models\User;
use App\Models\Standard;
use App\Models\AuditForm;
use App\Models\Question;
use App\Models\AuditFormAnswer;
use Illuminate\Http\Request;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\ReportReview;
use App\Mail\Report\ReviewResolved;
use App\Mail\Report\ReviewNotification;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ReportController extends Controller
{
    public function __construct() {
        $this->middleware('permission:report.manage,report.manage_assigned_resource', ['except' => [
            'index', 'edit'
        ]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('report.index'), 'name'=>"Reports"], ['name'=>"List of Reports"]
        ];
        if (request()->ajax()) {
            $report = Report::with(['audit'])->whereHas('audit');
            if($request->user()->hasRole('Client') || $request->user()->hasRole('Supplier')){
                $report->whereHas('audit', function($a) use($request){
                    $a->whereHas('schedule', function($s) use($request){
                        $s->whereHas('event', function($e) use($request){
                            $e->whereHas('users', function($eu) use($request){
                                $eu->where('modelable_type', 'App\Models\Company');
                                $eu->whereIn('modelable_id', $request->user()->companies->pluck('id')->toArray());
                            });
                        });
                    });
                });
            }

            if(! $request->user()->can('report.manage')){
                $report->whereHas('audit', function($a) use($request){
                    $a->whereHas('schedule', function($s) use($request){
                        $s->whereHas('event', function($e) use($request){
                            $e->whereHas('users', function($eu) use($request){
                                $eu->where('modelable_type', 'App\Models\User');
                                $eu->where('modelable_id', $request->user()->id);
                            });
                        });
                    });
                });
            }

            return Datatables::eloquent($report)
            ->addColumn('action', function(Report $report, Request $request) {
                if($request->user()->can('report.manage') || $request->user()->can('report.manage_assigned_resource')){
                    $actions = [];
                    if($request->user()->can('report.view')){
                        $actions[] = ['route' => route('report.show', $report->id), 'name' => 'Show', 'type' => 'href'];
                    }
                    if($request->user()->can('report.edit')){
                        $actions[] = ['route' => route('report.edit', $report->id), 'name' => 'Edit', 'type' => 'href'];
                    }
                    if($request->user()->can('report.editor')){
                        $actions[] = ['route' => route('report.editor', $report->id), 'name' => 'Report Editor', 'type' => 'href'];
                    }
                    return Utilities::actionButtons($actions);
                }
            })
            ->addColumn('schedule_title', function (Report $report, Request $request) {
                if($request->user()->can('schedule.manage')){
                    return '<a href="#" class="modal_button" data-action="'.route('schedule.edit', $report->audit->schedule->event_id).'">'. $report->audit->schedule->title .'</a>';
                }else{
                    return $report->audit->schedule->title;
                }
            })
            ->addColumn('audit_title', function (Report $report, Request $request) {
                if($request->user()->can('audit.manage')){
                    return '<a href="'.route('audit.show', $report->audit_id).'" target="_blank" class="">'. $report->audit->schedule->title .'</a>';
                }else{
                    return $report->audit->schedule->title;
                }
            })
            ->editColumn('updated_at', function (Report $report) {
                return $report->updated_at->diffForHumans() . ' | ' . $report->updatedByName;
            })
            ->editColumn('created_at', function (Report $report) {
                return $report->created_at->format('M d, Y') . ' | ' . $report->createdByName;
            })
            ->addColumn('status', function(Report $report) {
                return $report->statusDisplay;
            })->addColumn('google_drive_link', function(Report $report) {
                return $report->google_drive_link_display;
            })
            ->addColumn('final_pdf', function(Report $report) {
                return $report->final_pdf_display;
            })
            ->rawColumns(['action', 'schedule_title', 'audit_title', 'status', 'google_drive_link', 'final_pdf'])
            ->make(true);
        }
        return view('app.report.index', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('report.index'), 'name'=>"Reports"], ['name'=>"Create New Report"]
        ];
        $audits = Audit::orderBy('id', 'desc')->get();
        $templates = Template::where('is_deleted', false)->where('is_approved', true)->where('status', true)->whereIn('type', Template::$forReport)->orderBy('id', 'desc')->get();
        return view('app.report.create', compact('breadcrumbs', 'audits', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'audit_id' => 'required',
            'template_id' => 'required',
            'title' => 'required',
        ],[
            '*.required' => 'This field is required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $template = Template::find($data['template_id']);
            if($template){
                $q = $template->groups()->first()->questions()->first();
                $data['content'] = $q->text;
            }
            $report = Report::create($data);
            $report->processContent();
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Report added successfully!',
                        'redirect' => route('report.index')
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

    public function show(Report $report)
    {
       $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('report.index'), 'name'=>"Reports"], ['name'=>"Show Report"]
        ];
        

        return view('app.report.show', compact('breadcrumbs', 'report'));
    }

    public function edit(Report $report)
    {
       $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('report.index'), 'name'=>"Reports"], ['name'=>"Edit Report"]
        ];
        

        return view('app.report.edit', compact('breadcrumbs', 'report'));
    }

    public function update(Request $request, Report $report)
    {
        $validation = ['title' => 'required'];

        if(! $request->has('save_finish_later')){
            $validation['google_drive_link'] = ['required', 'url'];
            if($request->has('save_close')){
                $validation['final_pdf'] = ['required', 'url'];
            }
        }

        $validator = Validator::make($request->all(),$validation);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();

            if($request->has('save_close')){
                $data['status'] = 3;
            }else if($request->has('save_approve')){
                $data['status'] = 2;
            }else if($request->has('save_finish_later')){

            }else if($request->has('save_submit')){
                $data['status'] = 1;
            }

            $report = $report->update($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Report updated successfully!',
                        'redirect' => route('report.index')
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


    public function editor(Report $report)
    {
       $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('report.index'), 'name'=>"Reports"], ['name'=>"Report Editor"]
        ];
        
        $audit = $report->audit;
        $schedule = $audit->schedule;
        $company = $schedule->client;
        $spafs = $company->loadSpafForReport();
        $settings = Setting::first();
        $standards = Standard::whereIn('id', $audit->standardsIdsUsed())->get();

        $flags = $audit->flagsUsed();
        $flagsFormsUsed = $audit->flagsFormsUsed();
        // dd($flagsFormsUsed);
        $pageConfigs = ['layoutWidth' => 'full'];
        return view('app.report.editor', compact('breadcrumbs', 'report', 'schedule', 'company', 'spafs', 'settings', 'audit', 'standards', 'pageConfigs', 'flags', 'flagsFormsUsed'));
    }


    public function editorUpdate(Request $request, Report $report)
    {
        $validator = Validator::make($request->all(),[

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $report = $report->update($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Report updated successfully!',
                        'redirect' => route('report.index')
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }

    public function showQuestionSummary(AuditForm $auditForm, Question $question){
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"], ['name'=>"Audit Question Summary"], ['name' => $question->text]
        ];
        $audit = $auditForm->audit;
        $schedule = $audit->schedule;
        if (request()->ajax()) {
            $auditFormAnswers = AuditFormAnswer::whereIn('audit_form_header_id', $auditForm->headers->pluck('id'))->whereNotNull('value')
                                ->whereHas('question', function($q) use($question){
                                    $q->where('id', $question->id);
                                });
            return Datatables::eloquent($auditFormAnswers)
            ->addColumn('form_name', function (AuditFormAnswer $auditFormAnswer) {
                return $auditFormAnswer->header ? $auditFormAnswer->header->name : '';
            })
            ->editColumn('updated_at', function (AuditFormAnswer $auditFormAnswer) {
                return $auditFormAnswer->updated_at->diffForHumans() . ' | ' . $auditFormAnswer->updatedByName;
            })
            ->editColumn('created_at', function (AuditFormAnswer $auditFormAnswer) {
                return $auditFormAnswer->created_at->format('M d, Y') . ' | ' . $auditFormAnswer->createdByName;
            })
            ->rawColumns(['form_name'])
            ->make(true);
        }
        return view('app.report.questionSummary', compact('audit', 'auditForm', 'question', 'schedule', 'breadcrumbs'));
    }

    public function reviewCreate(Report $report){
        $targets = config('report.target_groups');
        return view('app.report.review.create', compact('report', 'targets'));
    }

    public function reviewStore(Report $report, Request $request){
        $validator = Validator::make($request->all(),
            [
                'target_group' => 'required',
                'message' => 'required',
                'file' => ['nullable', 'url']
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'msg' => 'Please check all errors']);
        }
        try {
            DB::beginTransaction();
            $review = $report->reviews()->create([
                'status' => 'Pending',
                'target_group' => $request->target_group,
                'message' => $request->message,
                'file' => $request->file
            ]);

            // mail to target group permission
            $permissionName = $request->target_group;
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                // Get all roles that have this permission
                $rolesWithPermission = Role::whereHas('permissions', function ($query) use ($permissionName) {
                    $query->where('name', $permissionName);
                })->pluck('id')->toArray();

                // Get all users that have any of these roles
                $usersWithPermission = User::whereHas('roles', function ($query) use ($rolesWithPermission) {
                    $query->whereIn('roles.id', $rolesWithPermission);
                })->pluck('email');
                if($usersWithPermission->count()){
                    Mail::to($usersWithPermission)->send(new ReviewNotification($review));
                }
            }
            
            
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Report Review created successfully!',
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

    public function reviewIndex(Request $request, Report $report){
        if (request()->ajax()) {
            $reviews = ReportReview::where('report_id', $report->id);
            return Datatables::eloquent($reviews)
            ->addColumn('action', function(ReportReview $review) use ($request, $report) {
                if($request->user()->can('auditForm.review')){

                }else{

                }
                if($review->status == 'Pending'){
                    return Utilities::actionButtons([['route' => route('report.review.resolve', $review), 'name' => 'Approve', 'type' => 'confirmWithNotes', 'text' => 'Are you sure to mark this as resolved?', 'title' => 'Mark as Resolved', 'confirmButtonText' => 'Resolve']]);
                }
            })
            ->addColumn('target_group', function(ReportReview $review) {
                return $review->target_group_display;
            })
            ->addColumn('file', function(ReportReview $review) {
                return $review->file_display;
            })
            ->addColumn('statusDisplay', function(ReportReview $review){
                return '<span class="text-'. $review->getStatusClass() .'">'. $review->status .'</span>';
            })
            ->editColumn('updated_at', function (ReportReview $review) {
                return $review->updated_at->diffForHumans() . ' | ' . $review->updatedByName;
            })
            ->editColumn('created_at', function (ReportReview $review) {
                return $review->created_at->format('M d, Y') . ' | ' . $review->createdByName;
            })
            ->rawColumns(['action', 'statusDisplay', 'file'])
            ->make(true);
        }
    }

    public function reviewResolve(ReportReview $reportReview, Request $request){
        try {
            DB::beginTransaction();
            $reportReview->update(['status' => 'Resolved', 'resolve_notes' => $request->notes]);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Report Review marked as resolved successfully!',
                        'table_id' => 'report_form_reviews',
                    ];

            if($reportReview->created_by_user->id != $request->user()->id){
                Mail::to($reportReview->created_by_user)->send(new ReviewResolved($reportReview));
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

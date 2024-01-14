<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Schedule;
use App\Models\Template;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Carbon\Carbon;
use App\Models\Setting;
use App\Mail\Audit\SendAudit;
use Illuminate\Support\Facades\Mail;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('audit.index'), 'name'=>"Audits"], ['name'=>"List of Audits"]
        ];
        if (request()->ajax()) {
            $audit = Audit::with('schedule')->whereHas('schedule');

            if($request->user()->can('audit.manage')){
                if($request->status != "all"){
                    $audit->where('status', $request->status);
                }
            }else{
                // only schedules that the user was tagged as a resource person
                $audit->whereHas('schedule', function($s) use($request){
                    $s->whereHas('event', function($e) use($request){
                        $e->whereHas('users', function($eu) use($request){
                            $eu->where('modelable_type', 'App\Models\User');
                            $eu->where('modelable_id', $request->user()->id);
                        });
                    });
                });
            }
            return Datatables::eloquent($audit)
            ->addColumn('action', function(Audit $audit) {
                return Utilities::actionButtons([['route' => route('audit.show', $audit->id), 'name' => 'Show', 'type' => 'href'],
                                             // ['route' => route('audit.destroy', $audit->id), 'name' => 'Delete', 'type' => 'confirmDelete', 'title' => 'Are you sure to delete this audit?', 'text' => 'Delete']
                                            ]);
            })
            ->addColumn('schedule_title', function (Audit $audit, Request $request) {
                if($request->user()->can('audit.manage')){
                    return '<a href="#" class="modal_button" data-action="'.route('schedule.edit', $audit->schedule->event_id).'">'. $audit->schedule->title .'</a>';
                }else{
                    return $audit->schedule->title;
                }
            })
            ->editColumn('updated_at', function (Audit $audit) {
                return $audit->updated_at->diffForHumans() . ' | ' . $audit->updatedByName;
            })
            ->editColumn('created_at', function (Audit $audit) {
                return $audit->created_at->format('M d, Y') . ' | ' . $audit->createdByName;
            })
            ->addColumn('status', function(Audit $audit) {
                return $audit->statusDisplay;
            })
            ->rawColumns(['action', 'schedule_title', 'status'])
            ->make(true);
        }
        return view('app.audit.index', [
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
            ['link'=>"/",'name'=>"Home"],['link'=> route('audit.index'), 'name'=>"Audits"], ['name'=>"Create New Audit"]
        ];
        $clients = Company::where('type', 'client')->get();
        $templates = Template::where('is_deleted', false)->where('is_approved', true)->where('status', true)->whereIn('type', Template::$forAudit)->get();
        return view('app.audit.create', compact('breadcrumbs', 'clients', 'templates'));
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
            'schedule_id' => 'required',
            'singleTemplates' => 'required',
        ],[
            '*.required' => 'This field is required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['status'] = 'pending';
            $audit = Audit::create($data);
            if($request->has('singleTemplates')){
                foreach($request->singleTemplates as $st){
                    $audit->forms()->create([
                        'template_id' => $st,
                        'isMultiple' => false
                    ]);
                }
            }
            if($request->has('multipleTemplates')){
                foreach($request->multipleTemplates as $mt){
                    $audit->forms()->create([
                        'template_id' => $mt,
                        'isMultiple' => true
                    ]);
                }
            }
            $schedule = $audit->schedule;
            if($data['sendEmailClient']){
                if($schedule){
                    if($schedule->client){
                        if($schedule->client->users->first()){
                            Mail::to($schedule->client->users->first())->send(new SendAudit($schedule->client->users->first(), $audit));
                        }
                    }
                }
            }

            if($data['sendEmailResource']){
                if($schedule){
                    if($schedule->event){
                        foreach($schedule->event->users()->where('modelable_type', 'App\Models\User')->get() as $eventUser){
                            Mail::to($eventUser->modelable)->send(new SendAudit($eventUser->modelable, $audit));
                        }
                    }
                }
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit added successfully!',
                        'redirect' => route('audit.index')
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
     * Display the specified resource.
     *
     * @param  \App\Models\Audit  $audit
     * @return \Illuminate\Http\Response
     */
    public function show(Audit $audit)
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('audit.index'), 'name'=>"Audits"], ['name'=>"Details"]
        ];
        $schedule = $audit->schedule;
        return view('app.audit.show', compact('audit', 'schedule', 'breadcrumbs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Audit  $audit
     * @return \Illuminate\Http\Response
     */
    public function edit(Audit $audit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Audit  $audit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Audit $audit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Audit  $audit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Audit $audit)
    {
        //
    }

    public function loadSchedulesFor(Company $company){

        $schedules = $company->schedules()->whereNotIn('id', $company->schedules()->whereHas('audit')->pluck('id'))->where('status', Setting::first()->forAuditStatus->name)->get();
        return view('app.schedule.load.company_schedule', compact('schedules'));
    }

    public function approve(Audit $audit, Request $request){
        try {
            DB::beginTransaction();
            $data = [];
            $unapprovedHeaders = [];

            foreach($audit->forms as $auditForm){
                foreach($auditForm->headers as $header){
                    if($header->status != 'approved'){
                        $unapprovedHeaders[] = $header;
                    }
                }
            }

            if(count($unapprovedHeaders) > 0){
                $output = ['success' => 0,
                        'msg' => 'Please approve all form answers first',
                    ];
                return response()->json($output);
            }

            if($request->has('notes')){
                $data['notes'] = $request->notes;
                $audit->update($data);
            }
            if($request->has('approve')){
                if(! $request->approve){
                    $data['status'] = 'pending';
                }else{
                    $data['status'] = 'completed';
                    $data['approved_at'] = Carbon::now()->format('Y-m-d H:i:s');
                }
            }
            $audit->update($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit successfully updated!',
                        'redirect' => route('audit.show', $audit)
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

    public function createForm(Audit $audit)
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('audit.index'), 'name'=>"Audits"], ['name'=>"Add Forms to Audit"], ['name'=> $audit->schedule->title]
        ];
        $templates = Template::where('is_deleted', false)->where('is_approved', true)->where('status', true)->whereIn('type', Template::$forAudit)->get();
        $auditFormsSingle = $audit->forms->where('isMultiple', 0)->pluck('template_id')->toArray();
        $auditFormsMultiple = $audit->forms->where('isMultiple', 1)->pluck('template_id')->toArray();
        return view('app.audit.createForm', compact('breadcrumbs', 'audit', 'templates', 'auditFormsSingle', 'auditFormsMultiple'));
    }

    public function storeForm(Request $request, Audit $audit){
        $validator = Validator::make($request->all(),[
            'singleTemplates' => 'required_without_all:multipleTemplates',
            'multipleTemplates' => 'required_without_all:singleTemplates',
        ],[
            '*.required' => 'This field is required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            if($request->has('singleTemplates')){
                foreach($request->singleTemplates as $st){
                    $audit->forms()->create([
                        'template_id' => $st,
                        'isMultiple' => false
                    ]);
                }
            }
            if($request->has('multipleTemplates')){
                foreach($request->multipleTemplates as $mt){
                    $audit->forms()->create([
                        'template_id' => $mt,
                        'isMultiple' => true
                    ]);
                }
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Added Forms to Audit successfully!',
                        'redirect' => route('audit.show', ['audit' => $audit])
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
}

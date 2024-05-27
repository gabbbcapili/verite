<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Audit;
use App\Models\Schedule;
use App\Models\Template;
use App\Models\Company;
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

class ReportController extends Controller
{
    public function __construct() {
        $this->middleware('permission:report.manage', ['except' => [
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
            $report = Report::with('audit')->whereHas('audit');
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
            return Datatables::eloquent($report)
            ->addColumn('action', function(Report $report, Request $request) {
                if($request->user()->can('report.manage')){
                    return Utilities::actionButtons([
                                        // ['route' => route('report.show', $report->id), 'name' => 'Show', 'type' => 'href'],
                                        ['route' => route('report.edit', $report->id), 'name' => 'Edit', 'type' => 'href'],
                                     // ['route' => route('report.destroy', $report->id), 'name' => 'Delete', 'type' => 'confirmDelete', 'title' => 'Are you sure to delete this report?', 'text' => 'Delete']
                                    ]);
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
            ->rawColumns(['action', 'schedule_title', 'audit_title'])
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
       $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('report.index'), 'name'=>"Reports"], ['name'=>"Edit Report"]
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
        return view('app.report.edit', compact('breadcrumbs', 'report', 'schedule', 'company', 'spafs', 'settings', 'audit', 'standards', 'pageConfigs', 'flags', 'flagsFormsUsed'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
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
}

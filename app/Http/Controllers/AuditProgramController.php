<?php

namespace App\Http\Controllers;

use App\Models\AuditProgram;
use Illuminate\Http\Request;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use App\Models\Company;
use App\Models\Schedule;
use Carbon\Carbon;

class AuditProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('schedule.auditProgram.index'), 'name'=>"Audit Programs"], ['name'=>"list of Audit Programs"]
        ];
        if (request()->ajax()) {
            $auditProgram = AuditProgram::with('schedule');
            return Datatables::eloquent($auditProgram)
            ->addColumn('action', function(AuditProgram $auditProgram) {
                if($auditProgram->auditProgramDates()->where('plotted', true)->count() > 0){
                    return Utilities::actionButtons([['route' => route('schedule.auditProgram.show', $auditProgram->id), 'name' => 'Show'],
                                                 ['route' => route('schedule.auditProgram.destroy', $auditProgram->id), 'name' => 'Delete', 'type' => 'confirmDelete', 'title' => 'Are you sure to delete this audit program?', 'text' => 'Delete']
                                                ]);
                }else{
                    return Utilities::actionButtons([['route' => route('schedule.auditProgram.show', $auditProgram->id), 'name' => 'Show'],
                                                 ['route' => route('schedule.auditProgram.edit', $auditProgram->id), 'name' => 'Edit'],
                                                 ['route' => route('schedule.auditProgram.destroy', $auditProgram->id), 'name' => 'Delete', 'type' => 'confirmDelete', 'title' => 'Are you sure to delete this audit program?', 'text' => 'Delete']
                                                ]);
                }
            })
            ->addColumn('copy_from', function (AuditProgram $auditProgram) {
                return '<a href="#" data-action="'.route('schedule.editNew', $auditProgram->schedule->event->id).'" class="modal_button">'. $auditProgram->schedule->title .'</a>';
            })
            ->editColumn('updated_at', function (AuditProgram $auditProgram) {
                return $auditProgram->updated_at->diffForHumans() . ' | ' . $auditProgram->updatedByName;
            })
            ->editColumn('created_at', function (AuditProgram $auditProgram) {
                return $auditProgram->created_at->format('M d, Y') . ' | ' . $auditProgram->createdByName;
            })
            ->rawColumns(['action', 'copy_from'])
            ->make(true);
        }
        return view('app.schedule.auditProgram.index', [
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
            ['link'=>"/",'name'=>"Home"],['link'=> route('schedule.auditProgram.index'), 'name'=>"Audit Programs"], ['name'=>"Create New Audit Program"]
        ];
        $clients = Company::where('type', 'client')->get();
        return view('app.schedule.auditProgram.create', compact('breadcrumbs', 'clients'));
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
            'schedule_id' => ['required'],
            'start_date' => ['required'],
            'frequency' => ['required','numeric','gt:0'],
            'length' => ['required','numeric','gt:0'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $auditProgram = AuditProgram::create($data);
            for($i = 1; $i <= $data['length']; $i++){
                $data['start_date'] = Carbon::parse($data['start_date'])->addMonths($data['frequency']);
                $auditProgram->auditProgramDates()->create([
                    'plot_date' => $data['start_date'],
                ]);
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Program added successfully!',
                        'redirect' => route('schedule.auditProgram.index'),
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
     * @param  \App\Models\AuditProgram  $auditProgram
     * @return \Illuminate\Http\Response
     */
    public function show(AuditProgram $auditProgram)
    {
        return view('app.schedule.auditProgram.show', compact('auditProgram'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AuditProgram  $auditProgram
     * @return \Illuminate\Http\Response
     */
    public function edit(AuditProgram $auditProgram)
    {
        $clients = Company::where('type', 'client')->get();
        $event = $auditProgram->schedule->event;
        $eventUsers = $event->users;
        $selectedClient = $eventUsers->where('role', 'Client')->first();
        $selectedSupplier = $eventUsers->where('role', 'Supplier')->first();
        return view('app.schedule.auditProgram.edit', compact('auditProgram', 'clients', 'selectedClient', 'selectedSupplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AuditProgram  $auditProgram
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AuditProgram $auditProgram)
    {
        $validator = Validator::make($request->all(),[
            'schedule_id' => ['required'],
            'start_date' => ['required'],
            'frequency' => ['required','numeric','gt:0'],
            'length' => ['required','numeric','gt:0'],        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();

            // $auditProgram = $auditProgram->update($data);
            $auditProgram->auditProgramDates()->delete();
            for($i = 1; $i <= $data['length']; $i++){
                $data['start_date'] = Carbon::parse($data['start_date'])->addMonths($data['frequency']);
                $auditProgram->auditProgramDates()->create([
                    'plot_date' => $data['start_date'],
                ]);
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Program updated successfully!',
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
     * @param  \App\Models\AuditProgram  $auditProgram
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuditProgram $auditProgram)
    {
        try {
            DB::beginTransaction();
            $auditProgram->auditProgramDates()->delete();
            $auditProgram = $auditProgram->delete();
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Program deleted successfully!',
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

    public function loadSchedulesFor(Company $company){
         $schedules = $company->schedules;
        return view('app.schedule.load.company_schedule', compact('schedules'));
    }
}

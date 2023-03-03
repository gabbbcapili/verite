<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\ScheduleStatus;
use Illuminate\Http\Request;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class ScheduleStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('settings.scheduleStatus.index'), 'name'=>"Schedule Status"], ['name'=>"list of Schedule Status"]
        ];
        if (request()->ajax()) {
            $schedulestatuses = ScheduleStatus::query();
            return Datatables::eloquent($schedulestatuses)
            ->addColumn('action', function(ScheduleStatus $scheduleStatus) {
                return Utilities::actionButtons([['route' => route('settings.scheduleStatus.edit', $scheduleStatus->id), 'name' => 'Edit'],
                                                 ['route' => route('settings.scheduleStatus.destroy', $scheduleStatus->id), 'name' => 'Delete', 'type' => 'confirmDelete', 'title' => 'Are you sure to delete this schedule status ' . $scheduleStatus->name . '?', 'text' => 'Delete']
                                                ]);
            })
            ->addColumn('nameDisplay', function(ScheduleStatus $scheduleStatus) {
                return $scheduleStatus->nameDisplay;
            })
            ->editColumn('updated_at', function (ScheduleStatus $scheduleStatus) {
                return $scheduleStatus->updated_at->diffForHumans() . ' | ' . $scheduleStatus->updatedByName;
            })
            ->editColumn('created_at', function (ScheduleStatus $scheduleStatus) {
                return $scheduleStatus->created_at->format('M d, Y') . ' | ' . $scheduleStatus->createdByName;
            })
            ->rawColumns(['action', 'nameDisplay'])
            ->make(true);
        }
        return view('app.setting.scheduleStatus.index', [
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
            ['link'=>"/",'name'=>"Home"],['link'=> route('settings.scheduleStatus.index'), 'name'=>"Schedule Statuses"], ['name'=>"Create New Schedule Status"]
        ];
        return view('app.setting.scheduleStatus.create', compact('breadcrumbs'));
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
            'name' => ['required', 'string', 'max:255', 'unique:schedule_status,name,{id},id,deleted_at,NULL'],
            'color' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $scheduleStatus = ScheduleStatus::create($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Schedule Status added successfully!',
                        'redirect' => route('settings.scheduleStatus.index'),
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
     * @param  \App\Models\ScheduleStatus  $scheduleStatus
     * @return \Illuminate\Http\Response
     */
    public function show(ScheduleStatus $scheduleStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ScheduleStatus  $scheduleStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(ScheduleStatus $scheduleStatus)
    {
        return view('app.setting.scheduleStatus.edit', compact('scheduleStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScheduleStatus  $scheduleStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScheduleStatus $scheduleStatus)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255', 'unique:schedule_status,name,' . $scheduleStatus->id . ',id,deleted_at,NULL'],
            'color' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $scheduleStatus = $scheduleStatus->update($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Schedule Status updated successfully!',
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
     * @param  \App\Models\ScheduleStatus  $scheduleStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScheduleStatus $scheduleStatus)
    {
        try {
            DB::beginTransaction();
            $scheduleStatus = $scheduleStatus->delete();
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Schedule Status deleted successfully!',
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

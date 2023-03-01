<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\AuditModel;
use Illuminate\Http\Request;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class AuditModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('settings.auditModel.index'), 'name'=>"Audit Models"], ['name'=>"list of Audit Models"]
        ];
        if (request()->ajax()) {
            $auditmodels = AuditModel::query();
            return Datatables::eloquent($auditmodels)
            ->addColumn('action', function(AuditModel $auditModel) {
                return Utilities::actionButtons([['route' => route('settings.auditModel.edit', $auditModel->id), 'name' => 'Edit'],
                                                 ['route' => route('settings.auditModel.destroy', $auditModel->id), 'name' => 'Delete', 'type' => 'confirmDelete', 'title' => 'Are you sure to delete this audit model ' . $auditModel->name . '?', 'text' => 'Delete']
                                                ]);
            })
            ->addColumn('nameDisplay', function(AuditModel $auditModel) {
                return $auditModel->nameDisplay;
            })
            ->editColumn('updated_at', function (AuditModel $auditModel) {
                return $auditModel->updated_at->diffForHumans() . ' | ' . $auditModel->updatedByName;
            })
            ->editColumn('created_at', function (AuditModel $auditModel) {
                return $auditModel->created_at->format('M d, Y') . ' | ' . $auditModel->createdByName;
            })
            ->rawColumns(['action', 'nameDisplay'])
            ->make(true);
        }
        return view('app.setting.auditModel.index', [
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
            ['link'=>"/",'name'=>"Home"],['link'=> route('settings.auditModel.index'), 'name'=>"Audit Models"], ['name'=>"Create New Audit Model"]
        ];
        return view('app.setting.auditModel.create', compact('breadcrumbs'));
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
            'name' => ['required', 'string', 'max:255', 'unique:audit_model,name,{id},id,deleted_at,NULL'],
            // 'color' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['color'] = '#086287';
            $auditModel = AuditModel::create($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Model added successfully!',
                        'redirect' => route('settings.auditModel.index'),
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
     * @param  \App\Models\AuditModel  $auditModel
     * @return \Illuminate\Http\Response
     */
    public function show(AuditModel $auditModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AuditModel  $auditModel
     * @return \Illuminate\Http\Response
     */
    public function edit(AuditModel $auditModel)
    {
        return view('app.setting.auditModel.edit', compact('auditModel'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AuditModel  $auditModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AuditModel $auditModel)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255', 'unique:audit_model,name,' . $auditModel->id . ',id,deleted_at,NULL'],
            // 'color' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $auditModel = $auditModel->update($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Model updated successfully!',
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
     * @param  \App\Models\AuditModel  $auditModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuditModel $auditModel)
    {
        try {
            DB::beginTransaction();
            $auditModel = $auditModel->delete();
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Model deleted successfully!',
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

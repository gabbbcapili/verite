<?php

namespace App\Http\Controllers;

use App\Models\Standard;
use Illuminate\Http\Request;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class StandardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('settings.standard.index'), 'name'=>"Audit Standard"], ['name'=>"list of Audit Standard"]
        ];
        if (request()->ajax()) {
            $standards = Standard::query();
            return Datatables::eloquent($standards)
            ->addColumn('action', function(Standard $standard) {
                return Utilities::actionButtons([['route' => route('settings.standard.edit', $standard->id), 'name' => 'Edit'],
                                                 ['route' => route('settings.standard.destroy', $standard->id), 'name' => 'Delete', 'type' => 'confirmDelete', 'title' => 'Are you sure to delete this audit standard ' . $standard->name . '?', 'text' => 'Delete']
                                                ]);
            })
            ->addColumn('nameDisplay', function(Standard $standard) {
                return $standard->nameDisplay;
            })
            ->editColumn('updated_at', function (Standard $standard) {
                return $standard->updated_at->diffForHumans() . ' | ' . $standard->updatedByName;
            })
            ->editColumn('created_at', function (Standard $standard) {
                return $standard->created_at->format('M d, Y') . ' | ' . $standard->createdByName;
            })
            ->rawColumns(['action', 'nameDisplay'])
            ->make(true);
        }
        return view('app.setting.standard.index', [
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
            ['link'=>"/",'name'=>"Home"],['link'=> route('settings.standard.index'), 'name'=>"Audit Standard"], ['name'=>"Create New Audit Standard"]
        ];
        return view('app.setting.standard.create', compact('breadcrumbs'));
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
            'name' => ['required', 'string', 'max:255', 'unique:standard,name,{id},id,deleted_at,NULL'],
            // 'color' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $standard = Standard::create($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Standard added successfully!',
                        'redirect' => route('settings.standard.index'),
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
     * @param  \App\Models\Standard  $standard
     * @return \Illuminate\Http\Response
     */
    public function show(Standard $standard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Standard  $standard
     * @return \Illuminate\Http\Response
     */
    public function edit(Standard $standard)
    {
        return view('app.setting.standard.edit', compact('standard'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Standard  $standard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Standard $standard)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255', 'unique:standard,name,' . $standard->id . ',id,deleted_at,NULL'],
            // 'color' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $standard = $standard->update($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Standard updated successfully!',
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
     * @param  \App\Models\Standard  $standard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Standard $standard)
    {
        try {
            DB::beginTransaction();
            $standard = $standard->delete();
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Standard deleted successfully!',
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

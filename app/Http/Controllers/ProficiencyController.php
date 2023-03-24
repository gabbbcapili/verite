<?php

namespace App\Http\Controllers;

use App\Models\Proficiency;
use Illuminate\Http\Request;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class ProficiencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('proficiency.index'), 'name'=>"Proficiencies"], ['name'=>"list of Proficiencies"]
        ];
        if (request()->ajax()) {
            $proficiency = Proficiency::query();
            return Datatables::eloquent($proficiency)
            ->addColumn('action', function(Proficiency $proficiency) {
                return Utilities::actionButtons([['route' => route('proficiency.edit', $proficiency->id), 'name' => 'Edit'],
                                                 ['route' => route('proficiency.destroy', $proficiency->id), 'name' => 'Delete', 'type' => 'confirmDelete', 'title' => 'Are you sure to delete this audit model ' . $proficiency->name . '?', 'text' => 'Delete']
                                                ]);
            })
            ->editColumn('updated_at', function (Proficiency $proficiency) {
                return $proficiency->updated_at->diffForHumans();
            })
            ->editColumn('created_at', function (Proficiency $proficiency) {
                return $proficiency->created_at->format('M d, Y');
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('app.setting.proficiency.index', [
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
            ['link'=>"/",'name'=>"Home"],['link'=> route('proficiency.index'), 'name'=>"Proficiencies"], ['name'=>"Create New Proficiency"]
        ];
        return view('app.setting.proficiency.create', compact('breadcrumbs'));
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
            'name' => ['required', 'string', 'max:255', 'unique:proficiency,name,{id},id,deleted_at,NULL'],
            // 'color' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['color'] = '#086287';
            $proficiency = Proficiency::create($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Audit Model added successfully!',
                        'redirect' => route('proficiency.index'),
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
     * @param  \App\Models\Proficiency  $proficiency
     * @return \Illuminate\Http\Response
     */
    public function show(Proficiency $proficiency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proficiency  $proficiency
     * @return \Illuminate\Http\Response
     */
    public function edit(Proficiency $proficiency)
    {
        return view('app.setting.proficiency.edit', compact('proficiency'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proficiency  $proficiency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proficiency $proficiency)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255', 'unique:proficiency,name,' . $proficiency->id . ',id,deleted_at,NULL'],
            // 'color' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $proficiency = $proficiency->update($data);
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
     * @param  \App\Models\Proficiency  $proficiency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proficiency $proficiency)
    {
        try {
            DB::beginTransaction();
            $proficiency = $proficiency->delete();
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

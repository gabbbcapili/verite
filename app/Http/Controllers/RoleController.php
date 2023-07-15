<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Carbon\Carbon;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('role.index'), 'name'=>"Roles & Privileges"], ['name'=>"List of Roles"]
        ];
        if (request()->ajax()) {
            $role = Role::where('is_deleted', 0)->orderBy('updated_at', 'desc');
            return Datatables::eloquent($role)
            ->addColumn('action', function(Role $role) {
                            if(! in_array($role->id, [1,2,3,4])){
                                return Utilities::actionButtons([['route' => route('role.show', $role->id), 'name' => 'Show'],['route' => route('role.edit', $role->id), 'name' => 'Edit'], ['route' => route('role.delete', $role->id), 'name' => 'Delete']]);
                            }else{
                                return Utilities::actionButtons([['route' => route('role.show', $role->id), 'name' => 'Show'],]);
                            }
                        })
            ->addColumn('privileges', function (Role $role) {
                return implode(', ', $role->permissions->pluck('display')->toArray());
            })
            ->editColumn('created_at', function (Role $role) {
                return $role->created_at->format('M d, Y');
            })
            ->editColumn('updated_at', function (Role $role) {
                return $role->updated_at->diffForHumans();
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('app.role.index', [
            'breadcrumbs' => $breadcrumbs,
        ]);
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
            'name' => ['required', 'unique:roles'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $role = Role::create(['name' => $data['name']]);
            if($request->has('permissions')){
                $permissions = Permission::whereIn('name', $data['permissions'])->get();
                $role->syncPermissions($permissions);
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Role added successfully!',
                        'redirect' => route('role.index'),
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

    public function create(){
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('role.index'), 'name'=>"Roles & Privileges"], ['name'=>"Create New Role"]
        ];
        return view('app.role.create', compact('breadcrumbs'));
    }

    public function show(Role $role){
        $roleList = $role->permissions->pluck('name')->toArray();
        return view('app.role.show', compact('role', 'roleList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $roleList = $role->permissions->pluck('name')->toArray();
        return view('app.role.edit', compact('role', 'roleList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'unique:roles,name,' . $role->id],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $role->update(['name' => $data['name']]);
            if($request->has('permissions')){
                $permissions = Permission::whereIn('name', $data['permissions'])->get();
                $role->syncPermissions($permissions);
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Role added successfully!',
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
     * @param  \App\Models\Role  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        try {
            DB::beginTransaction();
            $users = User::with("roles")->whereHas("roles", function($q) use($role) {
                $q->whereIn("name", [$role->name]);
            })->get();
            foreach($users as $user){
                $user->assignRole('Default');
            }
            $role->update(['is_deleted' => true]);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Role successfully deleted!'
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

    public function delete(Role $role){
        $action = route('role.destroy', ['role' => $role->id]);
        $title = 'role ' . $role->name;
        return view('layouts.delete', compact('action' , 'title'));
    }
}

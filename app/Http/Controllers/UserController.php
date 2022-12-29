<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\ClientSuppliers;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use App\Mail\Auth\ChangedRole;
use Illuminate\Support\Str;
use Laravel\Fortify\Rules\Password;
use App\Mail\Welcome;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('user.index'), 'name'=>"Users"], ['name'=>"list of Users"]
        ];
        if (request()->ajax()) {
            $user = User::with('roles')->whereHas("roles", function($q) {
                $q->whereNotIn('id', [3,4]);
            });
            if($request->role != "all"){
                $user = $user->whereHas("roles", function($q) use($request) {
                            $q->where('id', $request->role);
                        });
            }
            return Datatables::eloquent($user)
            ->addColumn('action', function(User $user) {
                            return Utilities::actionButtons([['route' => route('user.edit', $user->id), 'name' => 'Edit']]);
                        })
            ->addColumn('fullName', function(User $user) {
                            return $user->fullName;
                        })
            ->addColumn('role', function(User $user) {
                            return $user->getRoleNames()->first();
                        })
            ->editColumn('updated_at', function (User $user) {
                return $user->updated_at->diffForHumans() . ' | ' . $user->updatedByName;
            })
            ->editColumn('created_at', function (User $user) {
                return $user->created_at->format('M d, Y') . ' | ' . $user->createdByName;
            })
            ->filterColumn('fullName', function($query, $keyword) {
                    $query->whereRaw('CONCAT(first_name," ",last_name)  like ?', ["%{$keyword}%"]);
                })
            ->rawColumns(['action'])
            ->make(true);
        }
        $roles = Role::where('is_deleted', false)->whereNotIn('id', [3,4])->get();
        return view('app.user.index', [
            'breadcrumbs' => $breadcrumbs,
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'exists:roles,name'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $user =  User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make(Str::random(10)),
                'company_id' => 1,
            ]);
            $user->assignRole($request->role);
            $token = $user->generatePassworResetToken();
            Mail::to($user)->send(new ResetPassword($user, $token));
            Mail::to($user)->send(new Welcome($user));
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'User added successfully!',
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::where('is_deleted', false)->whereNotIn('id', [3,4])->get();
        return view('app.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            // 'role' => ['required', 'exists:roles,name']
            'password' => ['nullable', 'string', (new Password)->requireUppercase()
                            ->length(8)
                            ->requireNumeric()
                            ->requireSpecialCharacter()]
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->only(['first_name','last_name','email']);
            if($request->has('password')){
                if($request->password != null){
                    $data['password'] = Hash::make($request->password);
                }
            }
            $previousRole = $user->roles()->first()->name;
            $user->update($data);
            if($request->has('role')){
                $user->syncRoles([$request->role]);
                if($previousRole != $request->role){
                    Mail::to($user)->send(new ChangedRole($user));
                }

            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'User updated successfully!',
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\CreateSpaf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('supplier.index'), 'name'=>"Supplier Management"], ['name'=>"list of Suppliers"]
        ];
        if (request()->ajax()) {

            $user = User::with("roles")->with('spaf')->whereHas("roles", function($q) {
                $q->where("name", 'supplier');
            })->orderBy('updated_at', 'desc');
            // if($request->has('status')){
            //     $user->where('spaf.status', $request->status);
            // }
            return Datatables::eloquent($user)
            // ->filter(function ($query) {
            //         if (request()->has('status')) {
            //             $query->where('spaf.status', request()->status);
            //         }
            // }, true)
            ->addColumn('action', function(User $user) {
                            $html = '';
                            if(in_array($user->spaf->status, ['answered', 'completed'])){
                                    $html .= Utilities::actionButtons([['route' => route('spaf.show', $user->spaf->id), 'name' => 'Show', 'type' => 'href']]);
                                }
                            return $html;
                        })
            ->addColumn('fullName', function(User $user) {
                            return $user->fullName;
                        })
            ->addColumn('status', function(User $user) {
                            return $user->spaf->statusDisplay;
                        })
            ->editColumn('created_at', function (User $user) {
                return $user->created_at->format('M d, Y');
            })
            ->editColumn('updated_at', function (User $user) {
                return $user->updated_at->diffForHumans();
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
        }
        return view('app.supplier.index', [
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
            ['link'=>"/",'name'=>"Home"],['link'=> route('supplier.index'), 'name'=>"List Suppliers"], ['name'=>"Add Supplier"]
        ];
        $templates = Template::where('type', 'spaf')->where('is_deleted', false)->where('is_approved', true)->get();
        return view('app.supplier.create', compact('breadcrumbs', 'templates'));
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
            'template_id' => ['required', 'exists:template,id']
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
            ]);
            $spaf = $user->spaf()->create(['template_id' => $request->template_id]);
            $user->assignRole('Supplier');
            $token = $user->generatePassworResetToken();

            Mail::to($user)->send(new CreateSpaf($user, $spaf, $token));
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Supplier added successfully!',
                        'redirect' => route('supplier.index')
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
        //
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
        //
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

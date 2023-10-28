<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use App\Mail\Auth\WelcomeClient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\ClientSuppliers;
use App\Models\Company;
use App\Models\Country;


class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('supplier.index'), 'name'=>"Clients"], ['name'=>"List of Clients"]
        ];
        if (request()->ajax()) {
            $company = Company::with(['country', 'state'])->where('type', 'client');
            if($request->country){
                $company = $company->where('country_id', $request->country)->whereNotNull('country_id');
            }
            if($request->state){
                $company = $company->where('state_id', $request->state)->whereNotNull('state_id');
            }
            return Datatables::eloquent($company)
            ->addColumn('action', function(Company $company) {
                            return Utilities::actionButtons([['route' => route('supplier.addContact', $company->id), 'name' => 'Add', 'title' => 'Add Contact Person'],['route' => route('supplier.edit', $company->id), 'name' => 'Edit']]);
                        })
            ->addColumn('suppliers', function(Company $company) {
                            $html = '<div class="avatar-group">';
                            foreach($company->suppliers as $c){
                                $html .= '<a data-action="'. route('supplier.edit', $c->id) .'" class="modal_button"><div data-bs-toggle="tooltip" data-popup="tooltip-custom"data-bs-placement="top"class="avatar pull-up my-0"title="'. $c->companyDetails . '"><img src="'. $c->profilePhotoUrl .'" alt="Avatar" height="26" width="26"/></div></a>';
                            }
                            $html .= '</div>';
                            return $html;
                        })
            ->editColumn('country', function(Company $company) {
                            return $company->country ? $company->country->name : '';
                        })
            ->editColumn('state', function(Company $company) {
                            return $company->state ? $company->state->name : '';
                        })
            ->addColumn('suppliersExport', function(Company $company) {
                            $html = '';
                            foreach($company->suppliers as $c){
                                $html .= $c->companyDetails . ', ';
                            }
                            return $html;
                        })
            ->addColumn('company_display', function(Company $company) {
                            return $company->companyDisplay;
                        })
            ->addColumn('contact_persons', function(Company $company) {
                        $html = '<div class="avatar-group">';
                            foreach($company->users->where('status', 1) as $c){
                                $html .= '<a data-action="'. route('user.edit', $c->id) .'" class="modal_button"><div data-bs-toggle="tooltip" data-popup="tooltip-custom"data-bs-placement="top"class="avatar pull-up my-0"title="'. $c->fullName . '"><img src="'. $c->profilePhotoUrl .'" alt="Avatar" height="26" width="26"/></div></a>';
                            }
                            $html .= '</div>';
                            return $html;
                        })
            ->addColumn('contact_persons_inactive', function(Company $company) {
                        $html = '<div class="avatar-group">';
                            foreach($company->users->where('status', 0) as $c){
                                $html .= '<a data-action="'. route('user.edit', $c->id) .'" class="modal_button"><div data-bs-toggle="tooltip" data-popup="tooltip-custom"data-bs-placement="top"class="avatar pull-up my-0"title="'. $c->fullName . '"><img src="'. $c->profilePhotoUrl .'" alt="Avatar" height="26" width="26"/></div></a>';
                            }
                            $html .= '</div>';
                            return $html;
                        })
            ->addColumn('contactPersonsExport', function(Company $company) {
                            $html = '';
                            foreach($company->users->where('status', 1) as $c){
                                $html .= $c->fullName . ', ';
                            }
                            return $html;
                        })
            ->addColumn('contactPersonsInactiveExport', function(Company $company) {
                            $html = '';
                            foreach($company->users->where('status', 0) as $c){
                                $html .= $c->fullName . ', ';
                            }
                            return $html;
                        })
            ->editColumn('created_at', function (Company $company) {
                return $company->created_at->format('M d, Y'). ' | ' . $company->createdByName;
            })
            ->editColumn('updated_at', function (Company $company) {
                return $company->updated_at->diffForHumans(). ' | ' . $company->updatedByName;
            })
            ->rawColumns(['action', 'suppliers', 'contact_persons', 'company_display', 'contact_persons_inactive'])
            ->make(true);
        }
        $countries = Country::all();
        return view('app.client.index', [
            'breadcrumbs' => $breadcrumbs,
            'countries' => $countries,
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
            ['link'=>"/",'name'=>"Home"],['link'=> route('client.index'), 'name'=>"List Clients"], ['name'=>"Create New Client"]
        ];
        $suppliers = Company::where('type', 'supplier')->get();
        $countries = Country::all();
        return view('app.client.create', compact('breadcrumbs', 'suppliers', 'countries'));
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
            'company_name' => ['required'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'logo' => ['mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'country_id' => ['required'],
            'state_id' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $companyData = $request->only(['company_name', 'website', 'contact_number', 'address', 'logo', 'acronym', 'country_id', 'state_id']);
            $companyData['type'] = 'client';
            if($request->hasFile('logo')){
              $photo = $companyData['logo'];
              $new_name = 'logo_'  . sha1(time()) . '.' . $photo->getClientOriginalExtension();
              $photo->move(public_path('images/company/logos/') , $new_name);
              $companyData['logo'] = $new_name;
            }
            $company = Company::create($companyData);
            $userData = $request->only(['first_name', 'last_name', 'email', 'country_id', 'state_id']);
            $userData['password'] = Hash::make(Str::random(10));
            $user = $company->users()->create($userData);
            $user->assignRole('Client');
            if($request->has('suppliers')){
                foreach($request->suppliers as $s){
                    ClientSuppliers::create(['client_id' => $company->id, 'supplier_id' => $s]);
                }
            }
            $token = $user->generatePassworResetToken();
            // Mail::to($user)->send(new ResetPassword($user, $token));
            // Mail::to($user)->send(new WelcomeClient($user, $token));
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Client added successfully!',
                        'redirect' => route('client.index')
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

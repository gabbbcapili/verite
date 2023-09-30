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
use App\Mail\ResetPassword;
use App\Mail\Auth\WelcomeSupplier;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\ClientSuppliers;
use App\Models\Company;
use App\Models\Country;
use App\Models\State;

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
            ['link'=>"/",'name'=>"Home"],['link'=> route('supplier.index'), 'name'=>"Suppliers"], ['name'=>"List of Suppliers"]
        ];
        if (request()->ajax()) {
            $company = Company::where('type', 'supplier');
            return Datatables::eloquent($company)
            ->addColumn('action', function(Company $company) {
                            return Utilities::actionButtons([['route' => route('supplier.addContact', $company->id), 'name' => 'Add', 'title' => 'Add Contact Person'],['route' => route('supplier.edit', $company->id), 'name' => 'Edit']]);
                        })
            ->addColumn('clients', function(Company $company) {
                            $html = '<div class="avatar-group">';
                            foreach($company->clients as $c){
                                $html .= '<a data-action="'. route('supplier.edit', $c->id) .'" class="modal_button"><div data-bs-toggle="tooltip" data-popup="tooltip-custom"data-bs-placement="top"class="avatar pull-up my-0"title="'. $c->companyDetails . '"><img src="'. $c->profilePhotoUrl .'" alt="Avatar" height="26" width="26"/></div></a>';
                            }
                            $html .= '</div>';
                            return $html;
                        })
            ->addColumn('company_display', function(Company $company) {
                            return $company->companyDisplay;
                        })
            ->addColumn('clientsExport', function(Company $company) {
                            $html = '';
                            foreach($company->clients as $c){
                                $html .= $c->companyDetails . ', ';
                            }
                            return $html;
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
            ->rawColumns(['action', 'clients', 'contact_persons', 'company_display', 'contact_persons_inactive'])
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
            ['link'=>"/",'name'=>"Home"],['link'=> route('supplier.index'), 'name'=>"List Suppliers"], ['name'=>"Create New Supplier"]
        ];
        $clients = Company::where('type', 'client')->get();
        $countries = Country::all();
        return view('app.supplier.create', compact('breadcrumbs', 'clients', 'countries'));
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
            'clients' => ['required'],
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
            $companyData['type'] = 'supplier';
            if($request->hasFile('logo')){
              $photo = $companyData['logo'];
              $new_name = 'logo_'  . sha1(time()) . '.' . $photo->getClientOriginalExtension();
              $photo->move(public_path('images/company/logos/') , $new_name);
              $companyData['logo'] = $new_name;
            }
            $company = Company::create($companyData);
            $userData = $request->only(['first_name', 'last_name', 'email', 'country_id', 'state_id']);
            $userData['password'] = Hash::make('admin123');
            $user = $company->users()->create($userData);
            $user->assignRole('Supplier');
            if($request->has('clients')){
                foreach($request->clients as $c){
                    ClientSuppliers::create(['supplier_id' => $company->id, 'client_id' => $c]);
                }
            }

            $token = $user->generatePassworResetToken();
            // Mail::to($user)->send(new ResetPassword($user, $token));
            // Mail::to($user)->send(new WelcomeSupplier($user, $token));
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
    public function edit(Company $company)
    {
        $clients = Company::where('type', 'client')->get();
        $suppliers = Company::where('type', 'supplier')->get();
        $countries = Country::all();
        $states = State::where('country_id', $company->country_id)->get();
        return view('app.supplier.edit', compact('company', 'clients', 'suppliers', 'countries', 'states'));
    }

    public function addContact(Company $company)
    {
        return view('app.supplier.addContact', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(),[
            'company_name' => ['required'],
            'logo' => ['mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'country_id' => ['required'],
            'state_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $companyData = $request->only(['company_name', 'website', 'contact_number', 'address', 'logo', 'acronym', 'country_id', 'state_id']);
            if($request->hasFile('logo')){
              $photo = $companyData['logo'];
              $new_name = 'logo_'  . sha1(time()) . '.' . $photo->getClientOriginalExtension();
              $photo->move(public_path('images/company/logos/') , $new_name);
              $companyData['logo'] = $new_name;
            }
            $company->update($companyData);
            if($request->has('clients')){
                ClientSuppliers::where('supplier_id', $company->id)->delete();
                foreach($request->clients as $c){
                    ClientSuppliers::create(['supplier_id' => $company->id, 'client_id' => $c]);
                }
            }else{
                ClientSuppliers::where('supplier_id', $company->id)->delete();
            }
            if($request->has('suppliers')){
                ClientSuppliers::where('client_id', $company->id)->delete();
                foreach($request->suppliers as $c){
                    ClientSuppliers::create(['client_id' => $company->id, 'supplier_id' => $c]);
                }
            }else{
                ClientSuppliers::where('client_id', $company->id)->delete();
            }

            $company->users()->update($request->only(['country_id', 'state_id']));
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Supplier updated successfully!',
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

    public function storeContact(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $data['password'] = Hash::make(Str::random(10));
            $data['company_id'] = $company->id;
            $data['country_id'] = $company->country_id;
            $data['state_id'] = $company->state_id;
            $user = $company->users()->create($data);
            if($company->type == 'client'){
                $user->assignRole('Client');
            }elseif($company->type == 'supplier'){
                $user->assignRole('Supplier');
            }
            $token = $user->generatePassworResetToken();
            // Mail::to($user)->send(new ResetPassword($user, $token));
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Contact added successfully!',
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

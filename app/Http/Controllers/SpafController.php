<?php

namespace App\Http\Controllers;

use App\Models\Spaf;
use Illuminate\Http\Request;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;
use Carbon\Carbon;
use App\Models\Question;
use App\Mail\ResendSpaf;
use Illuminate\Support\Facades\Mail;
use App\Mail\CreateSpaf;
use App\Mail\Spaf\Completed;
use App\Mail\Spaf\Reminder;
use App\Models\Template;
use App\Models\User;
use App\Models\Company;

class SpafController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('spaf.index'), 'name'=>"Assessment Forms"], ['name'=>"List of Assessments"]
        ];
        if (request()->ajax()) {

            $spaf = Spaf::query();
            if($request->user()->can('spaf.manage')){
                if($request->status != "all"){
                    $spaf->where('status', $request->status);
                }
            }
            return Spaf::datatables($spaf, $request);
        }
        return view('app.spaf.index', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function clientIndex(Request $request){
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('spaf.index'), 'name'=>"Assessment Forms"], ['name'=>"List of Assessments"]
        ];
        if (request()->ajax()) {
            $spaf = Spaf::where('client_id', $request->user()->id);
            return Spaf::datatables($spaf, $request);
        }
        return view('app.spaf.index', [
                    'breadcrumbs' => $breadcrumbs,
                ]);
    }

    public function supplierIndex(Request $request){
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('spaf.index'), 'name'=>"Assessment Forms"], ['name'=>"List of Assessments"]
        ];
        if (request()->ajax()) {
            $spaf = Spaf::where('supplier_id', $request->user()->id);
            return Spaf::datatables($spaf, $request);
        }
        return view('app.spaf.index', [
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
            ['link'=>"/",'name'=>"Home"],['link'=> route('spaf.index'), 'name'=>"List Assessment Forms"], ['name'=>"Create New Assessment"]
        ];
        $templates = Template::where('is_deleted', false)->where('is_approved', true)->where('status', true)->get();
        $clients = Company::where('type', 'client')->get();
        return view('app.spaf.create', compact('breadcrumbs', 'templates', 'clients'));
    }

    public function loadSuppliers(Company $company){
        $suppliers = $company->suppliers;
        return view('app.spaf.load.suppliers', compact('suppliers'));
    }

    public function loadClientContactPersons(Company $company){
        $contactPersons = $company->users->where('status', true);
        return view('app.spaf.load.clientContactPersons', compact('contactPersons'));
    }

    public function loadSupplierContactPersons(Company $company){
        $contactPersons = $company->users->where('status', true);
        return view('app.spaf.load.supplierContactPersons', compact('contactPersons'));
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
            'client_id' => 'required',
            'client_company_id' => 'required',
            'template_id' => 'required'
        ],[
            '*.required' => 'This field is required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $spaf = Spaf::create($data);
            if($spaf->supplier){
                Mail::to($spaf->supplier)->send(new CreateSpaf($spaf->supplier, $spaf));
                Mail::to($spaf->client)->send(new CreateSpaf($spaf->client, $spaf));
            }else{
                Mail::to($spaf->client)->send(new CreateSpaf($spaf->client, $spaf));
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Assessment added successfully!',
                        'redirect' => route('spaf.index')
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
     * @param  \App\Models\Spaf  $spaf
     * @return \Illuminate\Http\Response
     */
    public function show(Spaf $spaf)
    {
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('spaf.show', $spaf), 'name'=>"Assessment Forms"], ['name' => 'Details']
        ];
        return view('app.spaf.show', compact('spaf', 'breadcrumbs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Spaf  $spaf
     * @return \Illuminate\Http\Response
     */
    public function edit(Spaf $spaf)
    {
        if($spaf->status == 'completed'){
            return redirect(route('spaf.show', $spaf));
        }
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('spaf.edit', $spaf), 'name'=>"Supplier Pre-assessment Form"], ['name' => 'Please fill out all necessary fields']
        ];
        return view('app.spaf.edit', compact('breadcrumbs', 'spaf'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Spaf  $spaf
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spaf $spaf)
    {
        $validation = [];
        if(! $request->has('save_finish_later')){
            foreach($spaf->template->questions  as $q){
                if($q->type == 'checkbox'){
                    $validation['checkbox.'. $q->id] = $q->required ? 'required' : '';
                }else{
                    $validation['question.'. $q->id] = $q->required ? 'required' : '';
                }

                if($q->type == 'email'){
                    $validation['question.'. $q->id] = $q->required ? ['required', 'email'] : ['nullable', 'email'];
                }
                if($q->type == 'number'){
                    $validation['question.'. $q->id] = $q->required ? ['required', 'numeric'] : ['nullable', 'numeric'];
                }
            }
        }
        $validator = Validator::make($request->all(),
            $validation,
        [
            'question.*.required' => 'This field is required.',
            'question.*.email' => 'This field must be a valid email address.',
            'question.*.numeric' => 'This field must be a number.',
            'checkbox.*.required' => 'This field is required.'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'msg' => 'Please check all errors']);
        }
        try {
            DB::beginTransaction();
            if(! $request->has('save_finish_later')){
                $spaf->update([
                    'status' => 'answered',
                    'completed_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }

            if($request->has('question')){
                foreach($request->question as $name => $q){
                    $spaf->answers()->updateOrCreate(['question_id' => $name,],['value' => $q,
                    ]);
                }
            }
            if($request->has('checkbox')){
                foreach($request->checkbox as $name => $q){
                    $spaf->answers()->updateOrCreate(['question_id' => $name,],[
                            'value' => implode(',', $q),
                        ]);
                }
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Spaf answered successfully!',
                        'redirect' => route('spaf.show', $spaf)
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
     * @param  \App\Models\Spaf  $spaf
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spaf $spaf)
    {
        //
    }

    public function approve(Spaf $spaf, Request $request){
        try {
            DB::beginTransaction();
            $data = [];
            if($request->has('notes')){
                $data['notes'] = $request->notes;
                $spaf->update($data);
            }
            if($request->has('approve')){
                if(! $request->approve){
                    $data['status'] = 'additional';
                    Mail::to($spaf->client)->send(new ResendSpaf($spaf->client, $spaf));
                    if($spaf->supplier){
                        Mail::to($spaf->supplier)->send(new ResendSpaf($spaf->supplier, $spaf));
                    }

                }else{
                    $data['status'] = 'completed';
                    Mail::to($spaf->client)->send(new Completed($spaf->client, $spaf));
                    if($spaf->supplier){
                        Mail::to($spaf->supplier)->send(new Completed($spaf->supplier, $spaf));
                    }
                    $data['approved_at'] = Carbon::now()->format('Y-m-d H:i:s');
                }
            }
            $spaf->update($data);
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Template successfully updated!',
                        'redirect' => route('spaf.show', $spaf)
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

    public function sendReminder(Spaf $spaf, Request $request){
        try {
            Mail::to($spaf->client)->send(new Reminder($spaf->client, $spaf));
            if($spaf->supplier){
                Mail::to($spaf->supplier)->send(new Reminder($spaf->supplier, $spaf));
            }
            $output = ['success' => 1,
                        'msg' => 'Reminder email successfully sent!',
                        'redirect' => route('spaf.show', $spaf)
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

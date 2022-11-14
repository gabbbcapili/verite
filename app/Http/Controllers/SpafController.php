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

class SpafController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
            ['link'=>"/",'name'=>"Home"],['link'=> route('spaf.show', $spaf), 'name'=>"Supplier Pre-assessment Form"], ['name' => 'Details']
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
                    $validation['checkbox.'. $q->id] = $q->required ? ['required', 'email'] : 'email';
                }
                if($q->type == 'number'){
                    $validation['checkbox.'. $q->id] = $q->required ? ['required', 'numeric'] : 'numeric';
                }
            }
        }

        // dd($validation);
        $validator = Validator::make($request->all(),
            $validation,
        [
            'question.*.required' => 'This field is required.',
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
            $data = [];
            if($request->has('notes')){
                $data['notes'] = $request->notes;
            }
            if($request->has('approve')){
                if(! $request->approve){
                    $data['status'] = 'additional';
                    Mail::to($spaf->user)->send(new ResendSpaf($spaf->user, $spaf));
                }else{
                    $data['status'] = 'completed';
                    $data['approved_at'] = Carbon::now()->format('Y-m-d H:i:s');
                }
            }
            DB::beginTransaction();
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
}

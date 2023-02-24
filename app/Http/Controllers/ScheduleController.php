<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\Company;
use App\Models\AuditModel;
use App\Models\ScheduleStatus;
use App\Models\Country;
use App\Models\User;
use App\Models\Utilities;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index(){
        return view('app.schedule.index');
    }

    public function getEvents(Request $request){
        try {
            $data = [];

            $events = Event::whereDate('start_date', '>=', $request->start)
                       ->whereDate('end_date', '<=', $request->end);

            if(! $request->user()->can('schedule.manage')){
                $events->whereHas('users', function ($q) use($request){
                    $q->where(function($q1) use($request){
                        $q1->where('modelable_id', $request->user()->id);
                        $q1->where('modelable_type', 'App\Models\User');
                    });
                    $q->orWhere(function($q2) use($request){
                        $q2->where('modelable_id', $request->user()->company_id);
                        $q2->where('modelable_type', 'App\Models\Company');
                    });
                });
            }
            foreach($events->get() as $event){
                $data[] = [
                    'id' => $event->id,
                    'url' => '',
                    'title' => $event->titleComputed,
                    'start' => Carbon::parse($event->start_date)->format('c'),
                    'end' => Carbon::parse($event->end_date)->format('c'),
                    'allDay' => true,
                    'extendedProps' => [
                        'calendar' => 'Personal'
                    ],
                ];
            }
            $output = $data;
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
        }
        return response()->json($output);
    }
    public function create(){
        $auditmodels = AuditModel::all();
        $schedulestatuses = ScheduleStatus::all();
        $countries = Country::all();
        return view('app.schedule.create', compact('auditmodels','schedulestatuses','countries'));
    }

    public function edit(Event $event){
        $auditmodels = AuditModel::all();
        $schedulestatuses = ScheduleStatus::all();
        $countries = Country::all();
        $schedule = $event->schedule ? $event->schedule : new Schedule();
        $client = $event->users->where('role', 'Client')->first();
        $supplier = $event->users->where('role', 'Supplier')->first();
        return view('app.schedule.edit', compact('auditmodels','schedulestatuses','countries', 'event', 'schedule', 'client', 'supplier'));
    }

    public function store(Request $request)
    {
        $type = $request->type;
        if($request->type == 'Audit Schedule'){
            $validation = [
                'start_end_date' => 'required',
                'type' => 'required',
                'title' => 'required',
                'client_company_id' => 'required',
                'audit_model' => 'required',
                'country' => 'required',
                'status' => 'required',
                'users' => ['required', 'min:1'],
                'users.*.id' => ['required']
            ];
        }else{
            $validation = [
                'start_end_date' => 'required',
                'type' => 'required'
            ];
        }

        $validator = Validator::make($request->all(),$validation, [
            '*.required' => 'This field is required',
            'users.*.*.required' => 'This field is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        if($request->user()->hasRole('Client') || $request->user()->hasRole('Supplier')){
            $eventUser = $request->user()->company->isAvailableOn($request->start_end_date);
            if($eventUser){
                return response()->json(['error' => ['start_end_date' => 'You already have schedule on this date entitled ' . $eventUser->event->TitleComputed]]);
            }
        }else{
            $eventUser = $request->user()->isAvailableOn($request->start_end_date);
            if($eventUser){
                return response()->json(['error' => ['start_end_date' => 'You already have schedule on this date entitled ' . $eventUser->event->TitleComputed]]);
            }
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $type = $request->type;
            $event['type'] = $type;
            $start_end = explode('to', $request->start_end_date);
            $event['start_date'] = $start_end[0];
            $event['end_date'] = array_key_exists(1, $start_end) ? $start_end[1] : $start_end[0];
            $event = Event::create($event);
            if($type == 'Audit Schedule'){
                foreach($request->users as $user){
                    EventUser::firstOrCreate([
                        'role' => $user['role'],
                        'event_id' => $event->id,
                        'modelable_id' => $user['id'],
                        'modelable_type' => 'App\Models\User',
                    ]);
                }
                EventUser::create([
                        'role' => 'Client',
                        'event_id' => $event->id,
                        'modelable_id' => $request->client_company_id,
                        'modelable_type' => 'App\Models\Company',
                        'blockable' => $request->has('supplier_company_id') ? 0 : 1,
                    ]);
                if($request->has('supplier_company_id')){
                    EventUser::create([
                        'role' => 'Supplier',
                        'event_id' => $event->id,
                        'modelable_id' => $request->supplier_company_id,
                        'modelable_type' => 'App\Models\Company',
                    ]);
                }
                $schedule = $request->only(['title','status','audit_model','city','due_date','report_submitted','cf_1','cf_2','cf_3','cf_4','cf_5']);
                $schedule['client_id'] = $request->has('supplier_company_id') ? $request->supplier_company_id : $request->client_company_id;
                $country = Country::find($request->country);
                $schedule['country'] = $country->name;
                $schedule['timezone'] = $country->timezone;
                $schedule['event_id'] = $event->id;
                $schedule = Schedule::create($schedule);
            }else{
                if($request->user()->hasRole('Client') || $request->user()->hasRole('Supplier')){
                    $eventuser = EventUser::create([
                        'role' => $type,
                        'event_id' => $event->id,
                        'modelable_id' => $request->user()->company_id,
                        'modelable_type' => 'App\Models\Company',
                    ]);
                }else{
                    $eventuser = EventUser::create([
                        'role' => $type,
                        'event_id' => $event->id,
                        'modelable_id' => $request->user()->id,
                        'modelable_type' => 'App\Models\User',
                    ]);
                }
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Schedule added successfully!',
                        'redirect' => route('schedule.index')
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

    public function update(Event $event, Request $request){
        $type = $request->type;
        if($request->type == 'Audit Schedule'){
            $validation = [
                'start_end_date' => 'required',
                'type' => 'required',
                'title' => 'required',
                'client_company_id' => 'required',
                'audit_model' => 'required',
                'country' => 'required',
                'status' => 'required',
                'users' => ['required', 'min:1'],
                'users.*.id' => ['required']
            ];
        }else{
            $validation = [
                'start_end_date' => 'required',
                'type' => 'required'
            ];
        }
        $validator = Validator::make($request->all(),$validation, [
            '*.required' => 'This field is required',
            'users.*.*.required' => 'This field is required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $type = $request->type;
            $data['type'] = $type;
            $start_end = explode('to', $request->start_end_date);
            $data['start_date'] = $start_end[0];
            $data['end_date'] = array_key_exists(1, $start_end) ? $start_end[1] : $start_end[0];
            $event->update($data);
            if($type == 'Audit Schedule'){
                if($request->has('phone')){
                    $newEventUsers = [];
                    $updatedEventUsers = [];
                    foreach($request->users as $user){
                        if(isset($user['id'])){
                            $updateEventUser = EventUser::findOrFail($user['id']);
                            $updatedEventUsers[] = $updateEventUser->id;
                            $updateEventUser->update($user);
                        }else{
                            $eventUser = new EventUser([
                                'role' => $user['role'],
                                'event_id' => $event->id,
                                'modelable_id' => $user['id'],
                                'modelable_type' => 'App\Models\User',
                            ]);
                            $newEventUsers[] = $eventUser;
                        }
                    }
                    $deleteEventUser = EventUser::where('event_id', $event->id)
                                    ->whereNotIn('id', $updatedEventUsers)
                                    ->delete();
                    if (!empty($newEventUsers)) {
                        $event->users()->saveMany($newEventUsers);
                     }
                }

                $event->users()->where('role', 'Client')->first()->update([
                        'role' => 'Client',
                        'event_id' => $event->id,
                        'modelable_id' => $request->client_company_id,
                        'modelable_type' => 'App\Models\Company',
                        'blockable' => $request->has('supplier_company_id') ? 0 : 1,
                    ]);
                if($request->has('supplier_company_id')){
                    EventUser::updateOrCreate([
                        'role' => 'Supplier',
                        'event_id' => $event->id,
                        'modelable_id' => $request->supplier_company_id,
                        'modelable_type' => 'App\Models\Company',
                    ],[
                        'modelable_id' => $request->supplier_company_id,
                    ]);
                }else{
                    $event->users()->where('role', 'Supplier')->first()->delete();
                }
                $schedule = $request->only(['title','status','audit_model','city','due_date','report_submitted','cf_1','cf_2','cf_3','cf_4','cf_5']);
                $schedule['client_id'] = $request->has('supplier_company_id') ? $request->supplier_company_id : $request->client_company_id;
                $country = Country::find($request->country);
                $schedule['country'] = $country->name;
                $schedule['timezone'] = $country->timezone;
                $schedule['event_id'] = $event->id;
                $schedule = $event->schedule()->update($schedule);
            }else{
                if($request->user()->hasRole('Client') || $request->user()->hasRole('Supplier')){
                    $event->users()->first()->update([
                        'role' => $type,
                        'event_id' => $event->id,
                        'modelable_id' => $request->user()->company_id,
                        'modelable_type' => 'App\Models\Company',
                    ]);
                }else{
                    $event->users()->first()->update([
                        'role' => $type,
                        'event_id' => $event->id,
                        'modelable_id' => $request->user()->id,
                        'modelable_type' => 'App\Models\User',
                    ]);
                }
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Schedule updated successfully!',
                        'redirect' => route('schedule.index')
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



    public function loadAvailableUsers(Request $request){
        try {
            $data['users'] = User::getAvailableAuditor($request->date);
            $data['clients'] = Company::getAvailableClient($request->date);
            $output = ['success' => 1,
                        'msg' => '',
                        'data' => $data,
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
            $output = ['success' => 0,
                        'msg' => env('APP_DEBUG') ? $e->getMessage() : 'Sorry something went wrong, please try again later.'
                    ];
        }
        return response()->json($output);
    }

    public function loadAvailableSuppliers(Company $company, Request $request){
        $suppliers = $company->getAvailableSuppliers($request->date);
        return view('app.spaf.load.suppliers', compact('suppliers'));
    }

    public function destroy(Event $event){
        try {
            DB::beginTransaction();
            if($event->users){
                $event->users()->delete();
            }

            if($event->schedule){
                $event->schedule()->delete();
            }

            $event->delete();
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Schedule Deleted Successfully!',
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

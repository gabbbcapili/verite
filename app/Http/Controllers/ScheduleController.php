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
use App\Models\State;
use App\Models\User;
use App\Models\Utilities;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Proficiency;
use App\Rules\NoAuditResourceOverlap;

class ScheduleController extends Controller
{
    public function index(){
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('schedule.index'), 'name'=>"Schedules"], ['name'=>"Calendar"]
        ];
        $auditors = User::auditors();
        $companies = Company::where('type', 'Client')->orWhere('type', 'Supplier')->get();
        return view('app.schedule.index', compact('breadcrumbs', 'auditors', 'companies'));
    }

    public function ganttChart(Request $request,){
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('schedule.ganttChart'), 'name'=>"Gantt Chart"], ['name'=>"Gantt Chart"]
        ];
        if($request->ajax()){
            $data = [];
            $events = Event::orderBy('start_date', 'asc');

            if($request->has('dateRange')){
               $start_end = explode('to', $request->dateRange);
               $start_date = $start_end[0];
               $end_date = array_key_exists(1, $start_end) ? $start_end[1] : $start_end[0];
               $events = $events->whereDate('start_date', '>=', $start_date)
                                ->whereDate('end_date', '<=', $end_date);
            }

            $events = Event::filter($events, $request);

            foreach($events->get() as $event){
                $start = new Carbon($event->start_date);
                $end = new Carbon($event->end_date);
                $diff = $start->diff($end)->days;
                $diff = $diff == 0 ? 1 : $diff + 1;
                $schedule = $event->schedule;
                $data[] = [
                    'id' => $event->id,
                    'parent' => 0,
                    'start_date' => $event->start_date,
                    'duration' => $diff,
                    'progress' => 1,
                    'text' => $event->ganttTitle,
                    'open' => true,
                    'tooltip' => $event->ganttTooltip,
                    'backgroundColor' => $event->type == 'Audit Schedule' ? $schedule ? $schedule->status_color : 'primary' : 'danger',
                    'textColor' => '#FFF',
                ];
            }

            return response()->json([
                'data' => $data,
                'links' => null,
            ]);
        }
        $auditors = User::auditors();
        $companies = Company::where('type', 'Client')->orWhere('type', 'Supplier')->get();
        return view('app.schedule.gantt', compact('breadcrumbs', 'auditors', 'companies'));
    }

    public function getEvents(Request $request){
        try {
            $data = [];
            $start = Carbon::parse($request->start)->subDays(30);
            $end = Carbon::parse($request->end)->addDays(30);

            $events = Event::whereDate('start_date', '>=', $start)
                       ->whereDate('end_date', '<=', $end);

            $events = Event::filter($events, $request);
            foreach($events->get() as $event){
                $schedule = $event->schedule;
                $data[] = [
                    'id' => $event->id,
                    'url' => '',
                    'title' => $event->titleComputed,
                    'start' => Carbon::parse($event->start_date)->format('c'),
                    'end' => Carbon::parse($event->end_date . ' 23:59:59')->format('c'),
                    'allDay' => true,
                    'extendedProps' => [
                        'calendar' => $event->type == 'Audit Schedule' ? $schedule ? $schedule->status_color : 'primary' : 'danger'
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
    public function create(Request $request){
        $auditmodels = AuditModel::all();
        $schedulestatuses = ScheduleStatus::all();
        $proficiencies = Proficiency::orderBy('name', 'asc')->get();
        $countries = Country::all();
        $date = $request->date;
        $companies = Company::all();
        $auditors = User::auditors();
        $schedules = Schedule::orderBy('created_at', 'desc')->get();
        return view('app.schedule.create', compact('auditmodels','schedulestatuses','countries', 'date', 'companies', 'proficiencies', 'schedules', 'auditors'));
    }

    public function edit(Event $event){
        $auditmodels = AuditModel::all();
        $schedulestatuses = ScheduleStatus::all();
        $countries = Country::all();
        $schedule = $event->schedule ? $event->schedule : new Schedule();
        $scheduleStatus = ScheduleStatus::withTrashed()->where('name', $schedule->status)->first();
        $next_stop = $scheduleStatus == null ? [] : explode(',', $scheduleStatus->next_stop);
        $client = $event->users->where('role', 'Client')->first();
        $supplier = $event->users->where('role', 'Supplier')->first();
        $companies = Company::all();
        $proficiencies = Proficiency::orderBy('name', 'asc')->get();
        $auditors = User::auditors();
        $countries = Country::all();
        $states = State::where('country_id', $event->country_id)->get();
        return view('app.schedule.edit', compact('auditmodels','schedulestatuses','countries', 'event', 'schedule', 'client', 'supplier', 'companies', 'scheduleStatus', 'next_stop', 'proficiencies', 'auditors', 'countries', 'states'));
    }

    public function editNew(Event $event, Request $request){
        $breadcrumbs = [
            ['link'=>"/",'name'=>"Home"],['link'=> route('schedule.index'), 'name'=>"Schedules"], ['name'=>"Edit Schedule"]
        ];
        $auditmodels = AuditModel::all();
        $schedulestatuses = ScheduleStatus::all();
        $countries = Country::all();
        $schedule = $event->schedule ? $event->schedule : new Schedule();
        $scheduleStatus = ScheduleStatus::withTrashed()->where('name', $schedule->status)->first();
        $next_stop = $scheduleStatus == null ? [] : explode(',', $scheduleStatus->next_stop);
        $client = $event->users->where('role', 'Client')->first();
        $supplier = $event->users->where('role', 'Supplier')->first();
        $companies = Company::all();
        $proficiencies = Proficiency::orderBy('name', 'asc')->get();
        $auditors = User::auditors();
        $countries = Country::all();
        $states = State::where('country_id', $event->country_id)->get();
        $userEvents = $request->user()->events()->where('status', 0)->where('event_id', $event->id)->first();
        $rejectedSchedules = $event->users()->withTrashed()->where('status', 2)->get();

        return view('app.schedule.editNew', compact('auditmodels','schedulestatuses','countries', 'event', 'schedule', 'client', 'supplier', 'companies', 'scheduleStatus', 'next_stop', 'proficiencies', 'auditors', 'breadcrumbs', 'countries', 'states', 'userEvents', 'rejectedSchedules'));
    }


    public function store(Request $request)
    {
        $type = $request->type;
        if($request->type == 'Audit Schedule'){
            $validation = [
                'start_end_date' => 'required',
                'type' => 'required',
                // 'title' => 'required',
                'client_company_id' => 'required',
                'audit_model' => 'required',
                'audit_model_type' => 'required',
                'country' => 'required',
                'status' => 'required',
                'turnaround_days' => ['required', 'integer', 'min:0', 'max:100'],
                'users' => ['required', 'min:1', new NoAuditResourceOverlap],
                'users.*.id' => ['required'],
                'users.*.start_end_date' => ['required']
            ];
        }else{
            if($request->user()->can('schedule.manage')){
                $validation = [
                    'start_end_date' => 'required',
                    'type' => 'required',
                    'unavailability_type' => 'required_if:type,Leave,Holiday,Unavailable',
                    'event_title' => 'required_if:type,Holiday Country',
                    'country_id' => 'required_if:type,Holiday Country',
                    'company_id' => 'required_if:unavailability_type,company',
                    'user_id' => 'required_if:unavailability_type,resource',
                ];
            }else{
                $validation = [
                    'start_end_date' => 'required',
                    'type' => 'required'
                ];
            }
        }

        $validator = Validator::make($request->all(),$validation, [
            'users.required' => 'The resource field is required.',
            'users.min' => 'Please add at least one resource.',
            '*.required' => 'This field is required',
            'users.*.*.required' => 'This field is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $type = $request->type;
        if($request->user()->hasRole('Client') || $request->user()->hasRole('Supplier')){
            $eventUser = $request->user()->company->isAvailableOn($request->start_end_date);
            if($eventUser){
                return response()->json(['error' => ['start_end_date' => 'You already have schedule on this date entitled ' . $eventUser->event->TitleComputed]]);
            }
        }else{
            if($type == 'Audit Schedule'){
            }else if($type == 'Holiday Country'){
                $start_end = explode('to', $request->start_end_date);
                $end_date = array_key_exists(1, $start_end) ? $start_end[1] : $start_end[0];
                $holidayCountryEvent = Event::where('country_id', $request->country_id)->where(function ($q) use ($start_end, $end_date) {
                                            $q->where('start_date', '<=', $start_end[0])
                                              ->where('end_date', '>=', $start_end[0])
                                              ->orWhere('start_date', '>=', $start_end[0])
                                              ->where('start_date', '<=', $end_date);
                                        });
                if($request->state_id){
                    $holidayCountryEvent = $holidayCountryEvent->where('state_id', $request->state_id);
                }else{
                    $holidayCountryEvent = $holidayCountryEvent->whereNull('state_id');
                }
                $holidayCountryEvent = $holidayCountryEvent->first();
                if($holidayCountryEvent){
                    return response()->json(['error' => ['start_end_date' => 'You already have schedule on this date entitled ' . $holidayCountryEvent->TitleComputed]]);
                }
            }else{
                if($request->user()->can('schedule.manage')){
                    if($request->company_id){
                        $eventUser = Company::where('id',$request->company_id)->first()->isAvailableOn($request->start_end_date);
                        if($eventUser){
                            return response()->json(['error' => ['start_end_date' => 'You already have schedule on this date entitled ' . $eventUser->event->TitleComputed]]);
                        }
                    }else{
                        $unavailableUser = User::where('id', $request->user_id)->first();
                        $eventUser = $unavailableUser->isAvailableOn($request->start_end_date);
                        if($eventUser){
                            return response()->json(['error' => ['start_end_date' => 'You already have schedule on this date entitled ' . $eventUser->event->TitleComputed]]);
                        }
                    }

                }else{
                    $eventUser = $request->user()->isAvailableOn($request->start_end_date);
                    if($eventUser){
                        return response()->json(['error' => ['start_end_date' => 'You already have schedule on this date entitled ' . $eventUser->event->TitleComputed]]);
                    }
                }
            }
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            if($type == 'Holiday Country'){
                $verite_users = User::where('country_id', $request->country_id)
                                    ->whereHas('companies', function ($q){
                                            $q->where('company_id', 1);
                                    });
                if($request->state_id){
                    $verite_users = $verite_users->where('state_id', $request->state_id);
                }
                $companies = Company::where('country_id', $request->country_id)->whereNotIn('id', [1]);
                if($request->state_id){
                    $companies = $companies->where('state_id', $request->state_id);
                }
                $event['type'] = $type;
                $event['country_id'] = $request->country_id;
                $event['state_id'] = $request->state_id;
                $event['title'] = $request->event_title;
                $start_end = explode('to', $request->start_end_date);
                $event['start_date'] = $start_end[0];
                $event['end_date'] = array_key_exists(1, $start_end) ? $start_end[1] : $start_end[0];
                $event = Event::create($event);
                foreach($verite_users->get() as $user){
                    $eventuser = EventUser::create([
                                'role' => $type,
                                'event_id' => $event->id,
                                'modelable_id' => $user->id,
                                'modelable_type' => 'App\Models\User',
                            ]);
                }
                foreach($companies->get() as $company_id){
                    $eventuser = EventUser::create([
                                'role' => $type,
                                'event_id' => $event->id,
                                'modelable_id' => $company_id->id,
                                'modelable_type' => 'App\Models\Company',
                            ]);
                }
            }else{
                $event['type'] = $type;
                $start_end = explode('to', $request->start_end_date);
                $event['start_date'] = $start_end[0];
                $event['end_date'] = array_key_exists(1, $start_end) ? $start_end[1] : $start_end[0];
                $event = Event::create($event);
                if($type == 'Audit Schedule'){
                    $supplier = null;
                    $schedule = $request->only(['status','audit_model','audit_model_type','with_completed_spaf', 'with_quotation','city','turnaround_days','report_submitted','cf_1','cf_2','cf_3','cf_4','cf_5']);
                    $scheduleStatus = ScheduleStatus::where('name', $schedule['status'])->first();
                    $schedule['status_color'] = $scheduleStatus ? $scheduleStatus->color : 'primary';
                    $blockable = $scheduleStatus ? $scheduleStatus->blockable : 1;
                    $client = EventUser::create([
                            'role' => 'Client',
                            'event_id' => $event->id,
                            'modelable_id' => $request->client_company_id,
                            'modelable_type' => 'App\Models\Company',
                            'blockable' => $request->has('supplier_company_id') ? 0 : $blockable,
                        ]);
                    if($request->has('supplier_company_id')){
                        $supplier = EventUser::create([
                            'role' => 'Supplier',
                            'event_id' => $event->id,
                            'modelable_id' => $request->supplier_company_id,
                            'modelable_type' => 'App\Models\Company',
                            'blockable' => $blockable,
                        ]);
                    }

                    $schedule['client_id'] = $request->has('supplier_company_id') ? $request->supplier_company_id : $request->client_company_id;
                    $country = Country::find($request->country);
                    $schedule['title'] = Schedule::computeTitle($client, $supplier, $country->acronym ,$event->start_date);
                    $schedule['country'] = $country->name;
                    $schedule['timezone'] = $country->timezone;
                    $schedule['event_id'] = $event->id;
                    $schedule['due_date'] = Carbon::now()->addDays($schedule['turnaround_days'])->format('Y-m-d');
                    $schedule = Schedule::create($schedule);
                    $schedule->scheduleStatusLogs()->create(['schedule_status_id' => $scheduleStatus->id]);

                    foreach($request->users as $user){
                        $eventUserStartEnd = EventUser::getStartEndDate($user['start_end_date']);
                        EventUser::firstOrCreate([
                            'role' => $user['role'],
                            'event_id' => $event->id,
                            'modelable_id' => $user['id'],
                            'modelable_type' => 'App\Models\User',
                            'start_date' => $eventUserStartEnd['eventUserStart'],
                            'end_date' => $eventUserStartEnd['eventUserEnd'],
                            'blockable' => $blockable,
                        ]);
                    }
                    
                }
                else{
                    if($request->user()->hasRole('Client') || $request->user()->hasRole('Supplier')){
                        $eventuser = EventUser::create([
                            'role' => $type,
                            'event_id' => $event->id,
                            'modelable_id' => $request->user()->company_id,
                            'modelable_type' => 'App\Models\Company',
                            'start_date' => $event['start_date'],
                            'end_date' => $event['end_date'],
                        ]);
                    }else{
                        if($request->user()->can('schedule.manage')){
                            if($request->company_id){
                                $eventuser = EventUser::create([
                                    'role' => $type,
                                    'event_id' => $event->id,
                                    'modelable_id' => $request->company_id,
                                    'modelable_type' => 'App\Models\Company',
                                    'start_date' => $event['start_date'],
                                    'end_date' => $event['end_date'],
                                ]);
                            }else{
                                $eventuser = EventUser::create([
                                    'role' => $type,
                                    'event_id' => $event->id,
                                    'modelable_id' => $request->user_id,
                                    'modelable_type' => 'App\Models\User',
                                    'start_date' => $event['start_date'],
                                    'end_date' => $event['end_date'],
                                ]);
                            }

                        }else{
                            $eventuser = EventUser::create([
                                'role' => $type,
                                'event_id' => $event->id,
                                'modelable_id' => $request->user()->id,
                                'modelable_type' => 'App\Models\User',
                                'start_date' => $event['start_date'],
                                'end_date' => $event['end_date'],
                            ]);
                        }
                    }
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
                // 'title' => 'required',
                'client_company_id' => 'required',
                'audit_model' => 'required',
                'audit_model_type' => 'required',
                'country' => 'required',
                'status' => 'required',
                'users' => ['required', 'min:1', new NoAuditResourceOverlap($event)],
                'users.*.id' => ['required'],
                'users.*.start_end_date' => ['required']
            ];
        }else{
            $validation = [
                'start_end_date' => 'required',
                'type' => 'required',
                'unavailability_type' => 'required_if:type,Leave,Holiday,Unavailable',
                'event_title' => 'required_if:type,Holiday Country',
                'country_id' => 'required_if:type,Holiday Country',
                'company_id' => 'required_if:unavailability_type,company',
                'user_id' => 'required_if:unavailability_type,resource',
            ];
        }
        $validator = Validator::make($request->all(),$validation, [
            'users.required' => 'The resource field is required.',
            'users.min' => 'Please add at least one resource.',
            '*.required' => 'This field is required',
            'users.*.*.required' => 'This field is required',
        ]);
        if($request->user()->can('schedule.manage')){
            if($type == 'Holiday Country'){
                $start_end = explode('to', $request->start_end_date);
                $end_date = array_key_exists(1, $start_end) ? $start_end[1] : $start_end[0];
                $holidayCountryEvent = Event::where('id', '!=', $event->id)->where('country_id', $request->country_id)
                                        ->where(function ($q) use ($start_end, $end_date) {
                                            $q->where('start_date', '<=', $start_end[0])
                                                  ->where('end_date', '>=', $start_end[0])
                                                  ->orWhere('start_date', '>=', $start_end[0])
                                                  ->where('start_date', '<=', $end_date);
                                        });
                if($request->state_id){
                    $holidayCountryEvent = $holidayCountryEvent->where('state_id', $request->state_id);
                }else{
                    $holidayCountryEvent = $holidayCountryEvent->whereNull('state_id');
                }
                $holidayCountryEvent = $holidayCountryEvent->first();
                if($holidayCountryEvent){
                    return response()->json(['error' => ['start_end_date' => 'You already have schedule on this date entitled ' . $holidayCountryEvent->TitleComputed]]);
                }
            }else{
                if($request->company_id != "Select Company"){
                    if($request->company_id != $event->users()->first()->modelable_id){
                        $eventUser = Company::where('id',$request->company_id)->first()->isAvailableOn($request->start_end_date);
                        if($eventUser){
                            return response()->json(['error' => ['start_end_date' => 'You already have schedule on this date entitled ' . $eventUser->event->TitleComputed]]);
                        }
                    }
                }else{
                    $unavailableUser = User::where('id', $request->user_id)->first();
                    if($unavailableUser != $event->users()->first()->modelable_id){
                        $eventUser = $unavailableUser->isAvailableOn($request->start_end_date);
                        if($eventUser){
                            return response()->json(['error' => ['start_end_date' => 'You already have schedule on this date entitled ' . $eventUser->event->TitleComputed]]);
                        }
                    }
                }
            }
        }
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
                $schedule = $request->only(['status','audit_model','audit_model_type','with_completed_spaf', 'with_quotation','city','due_date','report_submitted','cf_1','cf_2','cf_3','cf_4','cf_5']);
                $scheduleStatus = ScheduleStatus::where('name', $schedule['status'])->first();
                $schedule['status_color'] = $scheduleStatus ? $scheduleStatus->color : 'primary';
                $blockable = $scheduleStatus ? $scheduleStatus->blockable : 1;
                if($scheduleStatus->name != $event->schedule->status){
                    $event->schedule->scheduleStatusLogs()->create(['schedule_status_id' => $scheduleStatus->id]);
                }
                if($request->has('users')){
                    $updatedEventUsers = [];
                    foreach($request->users as $user){
                        $eventUserStartEnd = EventUser::getStartEndDate($user['start_end_date']);
                        if(isset($user['event_user_id'])){
                            $updateEventUser = EventUser::findOrFail($user['event_user_id']);
                            $updatedEventUsers[] = $updateEventUser->id;
                            $updateEventUser->update([
                                'modelable_id' => $user['id'],
                                'role' => $user['role'],
                                'blockable' => $blockable,
                                'start_date' => $eventUserStartEnd['eventUserStart'],
                                'end_date' => $eventUserStartEnd['eventUserEnd'],
                            ]);
                        }else{
                            $eventUser = EventUser::firstOrCreate([
                                'role' => $user['role'],
                                'event_id' => $event->id,
                                'modelable_id' => $user['id'],
                                'modelable_type' => 'App\Models\User',
                                'blockable' => $blockable,
                                'start_date' => $eventUserStartEnd['eventUserStart'],
                                'end_date' => $eventUserStartEnd['eventUserEnd'],
                            ]);
                            $updatedEventUsers[] = $eventUser->id;
                        }
                    }
                    $deleteEventUser = EventUser::where('event_id', $event->id)
                                    ->whereNotIn('id', $updatedEventUsers)
                                    ->where('role', '!=', 'Client')
                                    ->where('role', '!=', 'Supplier')
                                    ->delete();
                }
                $supplier = null;
                $client = $event->users()->where('role', 'Client')->first();
                $client->update([
                        'role' => 'Client',
                        'event_id' => $event->id,
                        'modelable_id' => $request->client_company_id,
                        'modelable_type' => 'App\Models\Company',
                        'blockable' => $request->has('supplier_company_id') ? 0 : $blockable,
                        'start_date' => $data['start_date'],
                        'end_date' => $data['end_date'],
                    ]);
                if($request->has('supplier_company_id')){
                    $supplier = EventUser::updateOrCreate([
                        'role' => 'Supplier',
                        'event_id' => $event->id,
                        'modelable_id' => $request->supplier_company_id,
                        'modelable_type' => 'App\Models\Company',
                        'start_date' => $data['start_date'],
                        'end_date' => $data['end_date'],
                    ],[
                        'modelable_id' => $request->supplier_company_id,
                        'blockable' => $blockable
                    ]);
                }else{
                    $event->users()->where('role', 'Supplier')->first() ? $event->users()->where('role', 'Supplier')->first()->delete() : '';
                }
                $schedule['client_id'] = $request->has('supplier_company_id') ? $request->supplier_company_id : $request->client_company_id;
                $country = Country::find($request->country);
                $schedule['country'] = $country->name;
                $schedule['timezone'] = $country->timezone;
                $schedule['event_id'] = $event->id;
                $schedule['title'] = Schedule::computeTitle($client, $supplier, $country->acronym ,$event->start_date);
                if(! $request->has('with_completed_spaf')){
                    $schedule['with_completed_spaf'] = false;
                }
                if(! $request->has('with_quotation')){
                    $schedule['with_quotation'] = false;
                }
                $schedule = $event->schedule()->update($schedule);
            }else if($type == 'Holiday Country'){
                $event->update(['title' => $request->event_title]);
                $event->users()->delete();
                $verite_users = User::where('country_id', $request->country_id)
                                    ->whereHas('companies', function ($q){
                                            $q->where('company_id', 1);
                                    });
                if($request->state_id){
                    $verite_users = $verite_users->where('state_id', $request->state_id);
                }
                $companies = Company::where('country_id', $request->country_id)->whereNotIn('id', [1]);
                if($request->state_id){
                    $companies = $companies->where('state_id', $request->state_id);
                }
                foreach($verite_users->get() as $user){
                    $eventuser = EventUser::create([
                                'role' => $type,
                                'event_id' => $event->id,
                                'modelable_id' => $user->id,
                                'modelable_type' => 'App\Models\User',
                                'start_date' => $data['start_date'],
                                'end_date' => $data['end_date'],
                            ]);
                }
                foreach($companies->get() as $company_id){
                    $eventuser = EventUser::create([
                                'role' => $type,
                                'event_id' => $event->id,
                                'modelable_id' => $company_id->id,
                                'modelable_type' => 'App\Models\Company',
                                'start_date' => $data['start_date'],
                                'end_date' => $data['end_date'],
                            ]);
                }
            }else{
                if($request->user()->hasRole('Client') || $request->user()->hasRole('Supplier')){
                    $event->users()->first()->update([
                        'role' => $type,
                        'event_id' => $event->id,
                        'modelable_id' => $request->user()->company_id,
                        'modelable_type' => 'App\Models\Company',
                        'start_date' => $data['start_date'],
                        'end_date' => $data['end_date'],
                    ]);
                }else{
                    if($request->user()->can('schedule.manage')){
                        if($request->unavailability_type == 'company'){
                            $eventuser = $event->users()->first()->update([
                                'role' => $type,
                                'event_id' => $event->id,
                                'modelable_id' => $request->company_id,
                                'modelable_type' => 'App\Models\Company',
                                'start_date' => $data['start_date'],
                                'end_date' => $data['end_date'],
                            ]);
                        }else{
                            $eventuser = $event->users()->first()->update([
                                'role' => $type,
                                'event_id' => $event->id,
                                'modelable_id' => $request->user_id,
                                'modelable_type' => 'App\Models\User',
                                'start_date' => $data['start_date'],
                                'end_date' => $data['end_date'],
                            ]);
                        }
                    }else{
                        $eventuser = $event->users()->first()->update([
                            'role' => $type,
                            'event_id' => $event->id,
                            'modelable_id' => $request->user()->id,
                            'modelable_type' => 'App\Models\User',
                            'start_date' => $data['start_date'],
                        'end_date' => $data['end_date'],
                        ]);
                    }
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
            $users = User::getAvailableAuditor($request->date);
            if($request->has('proficiencies')){
                $users = $users->filter(function ($candidate, $key) use ($request) {
                    $skills = explode(',',$candidate['skills']);
                    foreach($request->proficiencies as $p){
                        if(in_array($p, $skills)){
                            return $candidate;
                        }
                    }
                });
            }
            $data['users'] = $users;
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
                if($event->schedule->auditPrograms){
                    foreach($event->schedule->auditPrograms as $auditProgram){
                        $auditProgram->auditProgramDates()->delete();
                        $auditProgram->delete();
                    }
                }

                $event->schedule()->delete();
            }

            $event->delete();
            DB::commit();
            $output = ['success' => 1,
                        'msg' => 'Schedule Deleted Successfully!',
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

    public function loadSPAF(Company $company){
        return view('app.schedule.load.spaf', ['spafs' => $company->loadSpafForSchedule()]);
    }

    public function loadScheduleDetails(Schedule $schedule){
        try {
            $data['schedule'] = $schedule;
            $data['resource'] = $schedule->event->users->sortBy('modelable_type');
            $data['resource_count'] = $schedule->event->users()->where('modelable_type', 'App\Models\User')->count();
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

    public function checkAvailability(Request $request){
        $date = $request->date;

        $output = ['success' => true, 'msg' => ''];
        $client = Company::find($request->client);
        $supplier = Company::find($request->supplier);

        if($supplier){
            $bool = $supplier->isAvailableOn($date);
            if($bool){
                $output['success'] = false;
                $output['msg'] .= $supplier->full_name . ', ';
            }
        }else{
            if($client){
                $bool = $client->isAvailableOn($date);
                if($bool){
                    $output['success'] = false;
                    $output['msg'] .= $client->full_name . ', ';
                }
            }
        }
        if($request->users){
            foreach($request->users as $resource){
                $user = User::find($resource);
                if($user){
                    $bool = $user->isAvailableOn($date);
                    if($bool){
                        $output['success'] = false;
                        $output['msg'] .= $user->full_name . ', ';
                    }
                }
            }
        }
        if($output['success'] == false){
            $output['msg'] .= 'is not available on selected date are you sure to proceed?';
        }
        return response()->json($output);
    }

    public function eventUserStatusChange(EventUser $eventUser, $type, Request $request){
        try {
            DB::beginTransaction();
            $data = $request->all();
            $payload = ['status' => $type];
            if($request->has('notes')){
                $payload['notes'] = $request->notes;
            }
            $eventUser->update($payload);
            $msg = 'Successfully accepted the schedule';
            if($type == 2){
                $eventUser->delete();
                $msg = 'Successfully rejected the schedule';
            }
            DB::commit();
            $output = ['success' => 1,
                        'msg' => $msg,
                        'redirect' => route('schedule.editNew', $eventUser->event)
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

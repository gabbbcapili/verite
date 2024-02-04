<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Template;
use App\Models\Spaf;
use App\Models\Schedule;
use App\Models\ScheduleStatus;
use App\Models\Utilities;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Validator;

class StaterkitController extends Controller
{
    public function home(Request $request)
    {
        $breadcrumbs = [['link' => "home", 'name' => "Home"], ['name' => ""]];
        $totals = [];
        $totals['users'] = User::all()->count();
        $totals['suppliers'] = Role::find(3)->users->count();
        $totals['clients'] = Role::find(4)->users->count();
        $totals['spafs'] = Spaf::where('status', 'completed')->count();
        $totals['scheduleStatus'] = ScheduleStatus::all();
        if (request()->ajax()) {
            $schedules = Schedule::with('event');
            if(! $request->user()->can('schedule.manage')){
                $schedules->whereHas('event', function ($events) use ($request){
                    $events->whereHas('users', function ($q) use($request){
                        $q->where(function($q1) use($request){
                            $q1->where('modelable_id', $request->user()->id);
                            $q1->where('modelable_type', 'App\Models\User');
                        });
                        $q->orWhere(function($q2) use($request){
                            $q2->whereIn('modelable_id', $request->user()->companies()->pluck('company_id')->toArray());
                            $q2->where('modelable_type', 'App\Models\Company');
                        });
                    });
                });
            }
            if($request->scheduleStatus != "all"){
                $schedules = $schedules->where('status', $request->scheduleStatus);
            }
            if($request->has('dateRange')){
                $date = explode(' to ', $request->dateRange);
                $from = $date[0];
                $to = array_key_exists(1, $date) ? $date[1] : $date[0];
                $schedules = $schedules->whereHas('event', function ($q) use($from,$to){
                    $q->where('start_date', '>=', $from);
                    $q->where('end_date', '<=', $to);
                });
            }
            $scheduleStatus = ScheduleStatus::whereHas('schedules', function ($q) use($from,$to){
                    $q->whereHas('event', function ($q) use($from,$to){
                        $q->where('start_date', '>=', $from);
                        $q->where('end_date', '<=', $to);
                    });
                })->withCount('schedules')->get();
            $scheduleStatus = $schedules->get()->groupBy('status');
            return Datatables::eloquent($schedules)
            ->addColumn('titleDisplay', function(Schedule $schedule) {
                return '<a data-action="'. route('schedule.edit', $schedule->event_id) .'" class="modal_button" data-bs-toggle="tooltip" data-placement="top" title="'. $schedule->status .'"><span class="badge rounded-pill badge-light-'. $schedule->status_color .' me-1">'. $schedule->event->titleComputed .'</span></a>';
            })
            ->editColumn('updated_at', function (Schedule $schedule) {
                return $schedule->updated_at->diffForHumans() . ' | ' . $schedule->updatedByName;
            })
            ->addColumn('statuses', function (Schedule $schedule) {
                $html = '';
                foreach($schedule->scheduleStatusLogs as $ssl){
                    $scheduleStatus = $ssl->scheduleStatus;
                    $html .= '<span data-bs-toggle="tooltip" data-placement="top" title="'. $scheduleStatus->name .' | '. $ssl->updated_at->diffForHumans() .' | '. $ssl->updatedByName .'" class="badge rounded-pill badge-light-'. $scheduleStatus->color .' ">'. substr($scheduleStatus->name, 0, 1) .'</span>';
                }
                return $html;
            })
            ->addColumn('person_days', function (Schedule $schedule) {
                return $schedule->event->personDays . ' Days';
            })
            ->rawColumns(['action', 'titleDisplay', 'statuses'])
            ->with('scheduleStatus', $scheduleStatus)
            ->make(true);
        }
        return view('app.dashboard.index', compact('breadcrumbs', 'totals'));
    }

    public function getBadges(Request $request){
        try {
            $data = [];
            $user = $request->user();
            if($request->user()->hasRole('Supplier')){
                $data['badge_assessment_forms'] = $user->spafSupplier()->whereIn('status', ['pending', 'additional'])->count();
            }elseif($request->user()->hasRole('Client')){
                $data['badge_assessment_forms'] = $user->spafClient()->whereIn('status', ['pending', 'additional'])->count();
            }else{
                if($request->user()->can('template.manage') || $request->user()->can('template.approve')){
                    $data['badge_spaf'] = Template::where('type', 'spaf')->where('is_deleted', false)->where('is_approved', false)->count();
                    $data['badge_spaf_extension'] = Template::where('type', 'spaf_extension')->where('is_deleted', false)->where('is_approved', false)->count();
                    $data['badge_risk_management'] = Template::where('type', 'risk_management')->where('is_deleted', false)->where('is_approved', false)->count();
                    $data['badge_audit_template'] = Template::where('type', 'audit')->where('is_deleted', false)->where('is_approved', false)->count();
                    $data['badge_report_template'] = Template::where('type', 'report')->where('is_deleted', false)->where('is_approved', false)->count();
                    $data['badge_templates'] = $data['badge_spaf'] + $data['badge_spaf_extension'] + $data['badge_risk_management'] + $data['badge_audit_template'];
                }
                if($request->user()->can('spaf.manage') || $request->user()->can('spaf.approve')){
                    $data['badge_assessment_forms_admin'] = Spaf::whereIn('status',['pending', 'answered'])->count();
                }
            }

            $output = ['success' => 1,
                        'msg' => 'Fetched successfully!',
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

    public function setTheme($theme){
        $availTheme=['dark'=>'dark', 'light' => 'light'];
        if(array_key_exists($theme,$availTheme)){
            session()->put('theme',$theme);
        }
        return redirect()->back();
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuditProgram;
use App\Models\AuditProgramDate;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\Event;
use DB;
use App\Models\Schedule;
use App\Models\ScheduleStatusLog;

class PlotAuditProgram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditProgram:plot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $auditProgramDates = AuditProgramDate::whereMonth('plot_date', '=', Carbon::now()->month)
                                ->whereYear('plot_date', '=', Carbon::now()->year)
                                ->where('plotted', 0)->get();
            $setting = Setting::first();
            $status = $setting->auditProgramStatus()->withTrashed()->first();
            foreach($auditProgramDates as $auditProgramDate){
                $auditProgram = $auditProgramDate->auditProgram;
                $schedule = $auditProgram->schedule;
                $event = $schedule->event;
                $users = $event->users;
                $newEvent = $event->replicate()->fill([
                    'start_date' => $auditProgramDate->plot_date,
                    'end_date' => Carbon::parse($auditProgramDate->plot_date)->addDays(Carbon::parse($event->start_date)->diffInDays(Carbon::parse($event->end_date)))
                ])->toArray();
                $newEvent = Event::create($newEvent);

                foreach($users as $u){
                    $u->replicate()->fill([
                        'blockable' => $status->blockable,
                        'event_id' => $newEvent->id,
                    ])->save();
                }

                $newSchedule = $schedule->replicate()->fill([
                    'event_id' => $newEvent->id,
                    'status' => $status->name,
                    'status_color' => $status->color,
                    'due_date' => $auditProgramDate->plot_date,
                    'report_submitted' => $auditProgramDate->plot_date,
                    'is_manual_entry' => false,
                ])->toArray();
                $newSchedule = Schedule::create($newSchedule);
                $newSchedule->syncTitle();
                ScheduleStatusLog::create([
                    'schedule_id' => $newSchedule->id,
                    'schedule_status_id' => $status->id,
                ]);
                $auditProgramDate->update([
                    'plotted' => true,
                    'schedule_id' => $newSchedule->id,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
        }

    }
}

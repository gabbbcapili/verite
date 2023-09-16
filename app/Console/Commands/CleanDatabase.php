<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Template;
use App\Models\AuditForm;
use App\Models\ClientSuppliers;
use App\Models\Company;
use App\Models\Spaf;
use Illuminate\Support\Facades\DB;

class CleanDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Database';

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
            $users = User::where('status', 0)->get();
            $templates = Template::where('status', 0)->get();
            $companies = Company::whereIn('id', [60,49,47,45,43,15,13,70,61,54,51,50,48,46,44,14,62])->get();
            foreach($users as $user){
                foreach($user->spafSupplier as $spaf){
                    $spaf->delete();
                }

                foreach($user->spafClient as $spaf){
                    $spaf->delete();
                }

                foreach($user->events as $eventUser){
                    $event = $eventUser->event;

                    if($event){
                        if($event->schedule){
                            $schedule = $event->schedule;
                            foreach($schedule->auditPrograms as $auditProgram){
                                $auditProgram->forceDelete();
                            }
                            if($schedule->audit){
                                $audit = $schedule->audit;
                                if($audit->reports){
                                    foreach($audit->reports as $report){
                                        $report->delete();
                                    }
                                }
                                $audit->forceDelete();
                            }
                            $schedule->delete();
                        }
                        $event->forceDelete();
                    }
                    $eventUser->forceDelete();
                }
                $user->delete();
            }

            foreach($templates as $template){
                foreach($template->groups as $group){
                    foreach($group->questions as $question){
                        $question->delete();
                    }
                    $group->delete();
                }
                $spafs = Spaf::where('template_id', $template->id)->get();
                foreach($spafs as $spaf){
                    $spaf->delete();
                }
                $auditForms = AuditForm::where('template_id', $template->id);
                foreach($auditForms as $auditForm){
                    if($auditForm->audit){
                        $auditForm->audit->forceDelete();
                    }
                    $auditForm->forceDelete();
                }
                $template->delete();
            }

            foreach($companies as $company){
                ClientSuppliers::where('client_id', $company->id)->orWhere('supplier_id', $company->id)->delete();
                foreach($company->users as $user){
                    foreach($user->spafSupplier as $spaf){
                        $spaf->delete();
                    }

                    foreach($user->spafClient as $spaf){
                        $spaf->delete();
                    }
                }
                foreach($company->events as $eventUser){
                    $event = $eventUser->event;

                    if($event){
                        if($event->schedule){
                            $schedule = $event->schedule;
                            foreach($schedule->auditPrograms as $auditProgram){
                                $auditProgram->forceDelete();
                            }
                            if($schedule->audit){
                                $audit = $schedule->audit;
                                if($audit->reports){
                                    foreach($audit->reports as $report){
                                        $report->delete();
                                    }
                                }
                                $audit->forceDelete();
                            }
                            $schedule->delete();
                        }
                        $event->forceDelete();
                    }
                    $eventUser->forceDelete();
                }
                $company->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
        }
    }
}

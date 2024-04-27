<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuditFormHeader;
use App\Mail\Audit\FormReminder;
use Illuminate\Support\Facades\Mail;

class AuditFormReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditForm:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Provide Daily Reminder to Resource on completion of created Audit Form 1 day after its creation';

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
            $auditFormHeaders = AuditFormHeader::whereIn('status', ['open', 'partial'])->get();
            foreach($auditFormHeaders as $auditFormHeader){
                if($auditFormHeader->created_by_user && $auditFormHeader->form){
                    Mail::to($auditFormHeader->created_by_user)->send(new FormReminder($auditFormHeader));
                }
            }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). " Line:" . $e->getLine(). " Message:" . $e->getMessage());
        }
    }
}

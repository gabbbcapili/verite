<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = ['email_footer','spaf_completed','spaf_reminder','spaf_create',
                            'spaf_resend','user_reset','user_welcome', 'user_changed_role',
                            'admin_change_role_of','welcome_client','welcome_supplier', 'schedule_cf_1','schedule_cf_2','schedule_cf_3','schedule_cf_4','schedule_cf_5'];

    public function schedule_role_types(){
        return ['Lead Auditor', 'Second Auditor', 'Worker Interviewer', 'EHS Auditor', 'ASR', 'Interpreter', 'Observer'];
    }

    public function schedule_audit_model_types(){
        return ['Onsite', 'Remote', 'Hybrid'];
    }
}

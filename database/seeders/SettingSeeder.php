<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\AuditModel;
use App\Models\ScheduleStatus;
use App\Models\Country;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'email_footer' => 'Thanks,<br> Veritè',
            'spaf_completed' => 'Thank you for taking time answering our supplier pre-assessment form.',
            'spaf_reminder' => 'This is a gentle reminder to please answer this supplier pre-assesment form, kindly click the button below.',
            'spaf_create' => 'Please take time to answer this supplier pre-assesment form, kindly click the button below.',
            'spaf_resend' => 'Thank you for your time answering spaf however Veritè needs additional info,',
            'user_reset' => 'Please change your password immediately.',
            'user_welcome' => 'Hello thank you for registering in our site, please wait for our admin to change your role according to your position.',
            'welcome_client' => 'Welcome Client',
            'welcome_supplier' => 'Welcome Supplier',
            'audit_send' => 'A new Audit has been created to see the details click the button below.',
            'user_changed_role' => 'Hello! administrator changed your role click the button below to visit the site',
            'admin_change_role_of' => 'Please change the role of the user below',
            'schedule_cf_1' => 'Custom Field 1',
            'schedule_cf_2' => 'Custom Field 2',
            'schedule_cf_3' => 'Custom Field 3',
            'schedule_cf_4' => 'Custom Field 4',
            'schedule_cf_5' => 'Custom Field 5',
            'lead_auditor' => 1,
            'second_auditor' => 1,
            'worker_interviewer' => 1,
            'ehs_auditor' => 1,
            'asr' => 1,
            'interpreter' => 1,
            'observer' => 1,
            'status_for_audit_name' => 'Completed'
        ]);
        AuditModel::create([
            'name' => 'Comprehensive',
            'color' => '#13FB22'
        ]);
        AuditModel::create([
            'name' => 'Focused Investigation',
            'color' => '#FB1313'
        ]);
        AuditModel::create([
            'name' => 'Post-Assessment',
            'color' => '#D400FF'
        ]);

        ScheduleStatus::create([
            'name' => 'Completed',
            'color' => 'success'
        ]);
        ScheduleStatus::create([
            'name' => 'Cancelled',
            'color' => 'warning'
        ]);
        ScheduleStatus::create([
            'name' => 'Re-schedule',
            'color' => 'primary'
        ]);

        Country::create([
            'acronym' => 'PH',
            'name' => 'Philippines',
            'timezone' => 'GMT+08:00 Kuala Lumpur, Singapore'
        ]);

        Country::create([
            'acronym' => 'ASTL',
            'name' => 'Australia',
            'timezone' => 'GMT+12:00 Fiji Islands, Kamchatka, Marshall Islands'
        ]);
    }
}

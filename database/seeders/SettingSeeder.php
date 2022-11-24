<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

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
        ]);
    }
}

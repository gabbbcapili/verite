<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->longText('email_footer');
            // emails
            $table->longText('spaf_completed');
            $table->longText('spaf_reminder');
            $table->longText('spaf_create');
            $table->longText('spaf_resend');
            $table->longText('user_reset');
            $table->longText('user_welcome');
            $table->longText('user_changed_role');
            $table->longText('admin_change_role_of');
            $table->longText('welcome_client');
            $table->longText('welcome_supplier');
            // schedules
            $table->string('schedule_cf_1');
            $table->string('schedule_cf_2');
            $table->string('schedule_cf_3');
            $table->string('schedule_cf_4');
            $table->string('schedule_cf_5');
            $table->unsignedInteger('audit_program_default_status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}

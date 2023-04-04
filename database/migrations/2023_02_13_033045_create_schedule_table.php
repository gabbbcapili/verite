<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('event_id');
            $table->unsignedInteger('client_id');
            $table->string('status');
            $table->string('status_color');
            $table->string('audit_model');
            $table->string('audit_model_type');
            $table->boolean('with_completed_spaf')->default(0);
            $table->boolean('with_quotation')->default(0);
            $table->string('country');
            $table->string('timezone');
            $table->string('city')->nullable();
            $table->date('due_date')->nullable();
            $table->date('report_submitted')->nullable();
            $table->string('cf_1')->nullable();
            $table->string('cf_2')->nullable();
            $table->string('cf_3')->nullable();
            $table->string('cf_4')->nullable();
            $table->string('cf_5')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('schedule');
    }
}

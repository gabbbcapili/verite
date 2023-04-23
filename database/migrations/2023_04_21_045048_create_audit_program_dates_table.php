<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditProgramDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_program_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('audit_program_id');
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->date('plot_date');
            $table->boolean('plotted')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->foreign('audit_program_id')
              ->references('id')->on('audit_program')
              ->onDelete('cascade');
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
        Schema::dropIfExists('audit_program_dates');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->longText('text')->nullable();
            $table->string('type');
            $table->longText('for_checkbox')->nullable();
            $table->boolean('next_line')->default(0);
            $table->unsignedInteger('sort');
            $table->boolean('required')->default(0);
            $table->string('standards')->nullable();
            $table->string('flags')->nullable();
            $table->foreign('group_id')->references('id')->on('group')->onDelete('cascade');
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
        Schema::dropIfExists('question');
    }
}

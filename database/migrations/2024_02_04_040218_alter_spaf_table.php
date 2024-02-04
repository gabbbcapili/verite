<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSpafTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spaf', function (Blueprint $table) {
            $table->unsignedBigInteger('client_company_id');
            $table->unsignedBigInteger('supplier_company_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spaf', function (Blueprint $table) {
            $table->dropColumn('client_company_id');
            $table->dropColumn('supplier_company_id');
        });

    }
}

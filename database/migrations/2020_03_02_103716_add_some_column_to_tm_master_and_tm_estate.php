<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnToTmMasterAndTmEstate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TM_COMPANY', function (Blueprint $table) {
            $table->dateTime('insert_time_dw')->nullable();
            $table->dateTime('update_time_dw')->nullable();
        });
        Schema::table('TM_ESTATE', function (Blueprint $table) {
            $table->string('region_code',10)->nullable();
            $table->dateTime('start_valid')->nullable();
            $table->dateTime('end_valid')->nullable();
            $table->dateTime('insert_time_dw')->nullable();
            $table->dateTime('update_time_dw')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('TM_COMPANY', function (Blueprint $table) {
            $table->dropColumn('insert_time_dw');
            $table->dropColumn('update_time_dw');
        });
        Schema::table('TM_ESTATE', function (Blueprint $table) {
            $table->dropColumn('region_code');
            $table->dropColumn('start_valid');
            $table->dropColumn('end_valid');
            $table->dropColumn('insert_time_dw');
            $table->dropColumn('update_time_dw');
        });
    }
}

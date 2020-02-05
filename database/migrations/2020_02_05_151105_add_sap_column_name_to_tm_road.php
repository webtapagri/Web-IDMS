<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSapColumnNameToTmRoad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TM_ROAD', function (Blueprint $table) {
             $table->string('company_name',255)->nullable();
             $table->string('estate_name',255)->nullable();
             $table->string('afdeling_name',255)->nullable();
             $table->string('block_name',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('TM_ROAD', function (Blueprint $table) {
            $table->dropColumn('company_name');
            $table->dropColumn('estate_name');
            $table->dropColumn('afdeling_name');
            $table->dropColumn('block_name');
        });
    }
}

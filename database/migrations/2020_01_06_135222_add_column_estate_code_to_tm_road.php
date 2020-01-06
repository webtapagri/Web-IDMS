<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnEstateCodeToTmRoad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TM_ROAD', function (Blueprint $table) {
            $table->string('estate_code',100);
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
            $table->dropColumn('estate_code');
        });
    }
}

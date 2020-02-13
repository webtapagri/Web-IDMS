<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVRoadLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_ROAD_LOG");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_road_log.sql'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_ROAD_LOG");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_road_log.sql'));
    }
}

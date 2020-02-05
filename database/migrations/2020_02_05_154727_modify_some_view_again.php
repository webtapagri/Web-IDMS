<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySomeViewAgain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_LIST_PROGRESS_PERKERASAN_JALAN");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_list_progress_perkerasan_jalan.sql'));
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_ROAD");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_road.sql'));
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_ROAD_LOG");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_road_log.sql'));
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_REPORT_PROGRESS_PERKERASAN_JALAN");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_report_progress_perkerasan_jalan.sql'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_ROAD");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_road.sql'));
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_LIST_PROGRESS_PERKERASAN_JALAN");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_list_progress_perkerasan_jalan.sql'));
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_ROAD_LOG");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_road_log.sql'));
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_REPORT_PROGRESS_PERKERASAN_JALAN");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_report_progress_perkerasan_jalan.sql'));
    }
}

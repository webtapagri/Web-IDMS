<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyVProgressPerkerasanAddCondStatus extends Migration
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_LIST_PROGRESS_PERKERASAN_JALAN");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_list_progress_perkerasan_jalan.sql'));
    }
}

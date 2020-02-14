<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ViewGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'views:recreate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create / re-create views in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info('views oke');
		//
        \DB::unprepared("DROP VIEW IF EXISTS V_AFDELING");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_afdeling.sql'));
		//
        \DB::unprepared("DROP VIEW IF EXISTS V_BLOK");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_blok.sql'));
		//
        \DB::unprepared("DROP VIEW IF EXISTS V_ESTATE");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_estate.sql'));
		//
        \DB::unprepared("DROP VIEW IF EXISTS V_LIST_HISTORY_STATUS");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_list_history_status.sql'));
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_LIST_PROGRESS_PERKERASAN_JALAN");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_list_progress_perkerasan_jalan.sql'));
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_REPORT_PROGRESS_PERKERASAN_JALAN");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_report_progress_perkerasan_jalan.sql'));
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_ROAD");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_road.sql'));
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_ROAD_CATEGORY");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_road_category.sql'));
        //
        \DB::unprepared("DROP VIEW IF EXISTS V_ROAD_LOG");
        DB::unprepared(file_get_contents(__DIR__. '/../sql/v_road_log.sql'));
        //
        // \DB::unprepared("DROP VIEW IF EXISTS V_LIST_HISTORY_STATUS");
        // DB::unprepared(file_get_contents(__DIR__. '/../sql/v_road_status.sql'));
    }
}

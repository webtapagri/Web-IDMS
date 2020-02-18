<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB; 
use App\Jobs\FlushCache;

class ClearData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command untuk menghapus data IDMS';

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
		try{
			DB::unprepared("TRUNCATE TR_ROAD_LOG");
			echo "TR_ROAD_LOG truncated \r\n";
			DB::unprepared("TRUNCATE TR_ROAD_STATUS");
			echo "TR_ROAD_STATUS truncated \r\n";
			DB::unprepared("TRUNCATE TR_ROAD_PAVEMENT_PROGRESS");
			echo "TR_ROAD_PAVEMENT_PROGRESS truncated \r\n";
			DB::unprepared("DELETE FROM TM_ROAD");
			DB::unprepared("ALTER TABLE TM_ROAD AUTO_INCREMENT=0");
			echo "TM_ROAD truncated \r\n";
			DB::unprepared("DELETE FROM TM_ROAD_CATEGORY");
			DB::unprepared("ALTER TABLE TM_ROAD_CATEGORY AUTO_INCREMENT=0");
			echo "TM_ROAD_CATEGORY truncated \r\n";
			DB::unprepared("DELETE FROM TM_ROAD_STATUS");
			DB::unprepared("ALTER TABLE TM_ROAD_STATUS AUTO_INCREMENT=0");
			echo "TM_ROAD_STATUS truncated \r\n";
			
		}catch (\Throwable $e) {
            echo throwable_msg($e);
        }catch (\Exception $e) {
            echo exception_msg($e);
		}
		dispatch((new FlushCache)->onQueue('low'));
    }
}

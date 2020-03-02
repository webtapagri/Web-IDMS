<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB; 
use App\Jobs\FlushCache;

class flushmasterdataSAP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'idms:clean_master_sap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command untuk menghapus data master SAP';

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
			DB::unprepared("TRUNCATE TM_BLOCK");
			echo "TM_BLOCK truncated \r\n";
			DB::unprepared("DELETE FROM TM_AFDELING");
			DB::unprepared("ALTER TABLE TM_AFDELING AUTO_INCREMENT=0");
			echo "TM_AFDELING truncated \r\n";
			DB::unprepared("DELETE FROM TM_ESTATE");
			DB::unprepared("ALTER TABLE TM_ESTATE AUTO_INCREMENT=0");
			echo "TM_ESTATE truncated \r\n";
			DB::unprepared("DELETE FROM TM_COMPANY");
			DB::unprepared("ALTER TABLE TM_COMPANY AUTO_INCREMENT=0");
			echo "TM_COMPANY truncated \r\n";
			
		}catch (\Throwable $e) {
            echo throwable_msg($e);
        }catch (\Exception $e) {
            echo exception_msg($e);
		}
		dispatch((new FlushCache)->onQueue('low'));
    }
}

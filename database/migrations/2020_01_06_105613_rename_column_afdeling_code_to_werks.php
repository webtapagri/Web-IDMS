<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnAfdelingCodeToWerks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TM_ROAD', function (Blueprint $table) {
			$table->renameColumn('estate_code', 'werks');
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
			$table->renameColumn('werks', 'estate_code');
		});
    }
}

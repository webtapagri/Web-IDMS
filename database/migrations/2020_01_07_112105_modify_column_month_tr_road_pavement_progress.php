<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnMonthTrRoadPavementProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TR_ROAD_PAVEMENT_PROGRESS', function ($table) {
			$table->string('month', 10)->change();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('TR_ROAD_PAVEMENT_PROGRESS', function ($table) {
			$table->integer('month')->change();
		});
    }
}

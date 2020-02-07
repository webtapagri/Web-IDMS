<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConstraintToPavementProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TR_ROAD_PAVEMENT_PROGRESS', function (Blueprint $table) {
			$table->index('road_id');
			$table->foreign('road_id')->references('id')->on('TM_ROAD')->onDelete('cascade');
		});		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		
    }
}

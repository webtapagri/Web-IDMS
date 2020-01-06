<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToTrTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TR_ROAD_LOG', function (Blueprint $table) {
			$table->foreign('road_id')->references('id')->on('TM_ROAD')->onDelete('cascade');
        });
        Schema::table('TR_ROAD_STATUS', function (Blueprint $table) {
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
        Schema::table('TR_ROAD_LOG', function (Blueprint $table) {
            $table->dropForeign('tm_road_log_road_id_foreign');
        });
        Schema::table('TR_ROAD_STATUS', function (Blueprint $table) {
            $table->dropForeign('tm_road_log_road_id_foreign');
        });
    }
}

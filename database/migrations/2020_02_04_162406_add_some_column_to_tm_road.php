<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnToTmRoad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TM_ROAD', function (Blueprint $table) {
            $table->integer('total_length');
            $table->string('asset_code',100);
			$table->integer('segment');
			$table->unsignedInteger('status_id');
            $table->unsignedInteger('category_id');
			$table->foreign('status_id')->references('id')->on('TM_ROAD_STATUS')->onDelete('cascade');
			$table->foreign('category_id')->references('id')->on('TM_ROAD_CATEGORY')->onDelete('cascade');
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
            $table->dropColumn('total_length');
            $table->dropColumn('asset_code');
			$table->dropColumn('segment');
			$table->dropForeign('tm_road_status_id_foreign'); 
            $table->dropColumn('status_id');
			$table->dropForeign('tm_road_category_id_foreign'); 
            $table->dropColumn('category_id');
        });
    }
}

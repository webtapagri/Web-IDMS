<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoadNameAndCodeTrRoadStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TR_ROAD_STATUS', function (Blueprint $table) {
            $table->string('road_code',100);
            $table->string('road_name',100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('TR_ROAD_STATUS', function (Blueprint $table) {
            $table->dropColumn('road_code');
            $table->dropColumn('road_name');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTrRoadLogMakeAssetCodeNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TR_ROAD_LOG', function (Blueprint $table) {
			$table->string('asset_code',100)->nullable()->change();
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
			$table->string('asset_code',100)->change();
		});
    }
}

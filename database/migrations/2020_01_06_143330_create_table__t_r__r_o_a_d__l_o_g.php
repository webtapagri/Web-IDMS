<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTRROADLOG extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TR_ROAD_LOG', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('road_id');
            $table->integer('total_length');
            $table->string('asset_code',100);
            $table->unsignedInteger('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TR_ROAD_LOG');
    }
}

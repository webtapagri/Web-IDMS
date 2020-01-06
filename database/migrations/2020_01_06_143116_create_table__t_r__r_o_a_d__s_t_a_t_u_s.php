<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTRROADSTATUS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TR_ROAD_STATUS', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('road_id');
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('updated_by');
			$table->foreign('status_id')->references('id')->on('TM_ROAD_STATUS')->onDelete('cascade');
			$table->foreign('category_id')->references('id')->on('TM_ROAD_CATEGORY')->onDelete('cascade');
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
        Schema::dropIfExists('TR_ROAD_STATUS');
    }
}

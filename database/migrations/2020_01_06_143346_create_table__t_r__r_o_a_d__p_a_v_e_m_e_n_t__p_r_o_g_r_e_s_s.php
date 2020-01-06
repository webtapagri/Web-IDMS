<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTRROADPAVEMENTPROGRESS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TR_ROAD_PAVEMENT_PROGRESS', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('road_id');
            $table->integer('length');
            $table->integer('month');
            $table->integer('year');
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
        Schema::dropIfExists('TR_ROAD_PAVEMENT_PROGRESS');
    }
}

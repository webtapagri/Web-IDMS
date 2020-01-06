<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TM_ROAD', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_code',100);
            $table->string('estate_code',100);
            $table->string('afdeling_code',100);
            $table->string('block_code',100);
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('category_id');
            $table->string('road_code',100);
            $table->string('road_name',100);
            $table->string('asset_code',100);
            $table->string('segment',100);
            $table->integer('total_length');
            $table->boolean('status_pekerasan');
            $table->boolean('status_aktif');
			$table->foreign('status_id')->references('id')->on('TM_ROAD_STATUS')->onDelete('cascade');
			$table->foreign('category_id')->references('id')->on('TM_ROAD_CATEGORY')->onDelete('cascade');
			$table->softDeletes();
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
        Schema::dropIfExists('TM_ROAD');
    }
}

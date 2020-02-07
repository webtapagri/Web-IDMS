<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexTmRoadWerks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TM_ROAD', function (Blueprint $table) {
            $table->index('werks');
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
            $table->dropIndex('werks');
        });
    }
}

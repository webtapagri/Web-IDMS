<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartEndValidTmBlock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TM_BLOCK', function (Blueprint $table) {
            $table->date('start_valid');
            $table->date('end_valid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('TM_BLOCK', function (Blueprint $table) {
            $table->dropColumn('start_valid');
            $table->dropColumn('end_valid');
        });
    }
}

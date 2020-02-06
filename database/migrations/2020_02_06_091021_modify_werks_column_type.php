<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyWerksColumnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TM_ROAD', function (Blueprint $table) {
			$table->integer('werks')->change();
		});
        Schema::table('TM_ESTATE', function (Blueprint $table) {
			$table->integer('werks')->change();
		});
        Schema::table('TM_AFDELING', function (Blueprint $table) {
			$table->integer('werks')->change();
		});
        Schema::table('TM_BLOCK', function (Blueprint $table) {
			$table->integer('werks')->change();
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
			$table->string('werks',50)->change();
		});
		Schema::table('TM_ESTATE', function (Blueprint $table) {
			$table->string('werks',50)->change();
		});
		Schema::table('TM_AFDELING', function (Blueprint $table) {
			$table->string('werks',50)->change();
		});
		Schema::table('TM_BLOCK', function (Blueprint $table) {
			$table->string('werks',50)->change();
		});
        
    }
}

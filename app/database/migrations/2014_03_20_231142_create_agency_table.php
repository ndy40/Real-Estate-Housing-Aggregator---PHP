<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAgencyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('agency', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('crawler');
            $table->integer('country_id')->unsigned();
            $table->boolean('enabled')->default(false);
            $table->boolean('auto_publish')->default(false);
        });
        
        Schema::table('agency', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on("country");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        
        Schema::table('agency', function (Blueprint $table) {
            $table->dropForeign('agency_country_id_foreign');
        });
        
		Schema::drop('agency');
	}

}

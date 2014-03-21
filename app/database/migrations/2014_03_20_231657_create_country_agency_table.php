<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateCountryAgencyTable extends Migration {

	/**
	 * Run the migrations.Creates country_agency table.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('country_agency', function (Blueprint $table) {
            $table->integer('country')->unsigned();
            $table->integer('agency')->unsigned();
            $table->boolean('enabled');
        });
        
        Schema::table('country_agency', function (Blueprint $table) {
           $table->foreign('country')->references('id')->on('country');
           $table->foreign('agency')-> references('id')->on('agency');
        });
	}

	/**
	 * Reverse the migrations. Drops foreign keys and table country_agency
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('country_agency', function (Blueprint $table) {
            $table->dropForeign('country_agency_country_foreign');
            $table->dropForeign('country_agency_agency_foreign');
        });
        
        Schema::drop('country_agency');
	}

}

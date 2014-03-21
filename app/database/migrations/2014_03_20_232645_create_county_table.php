<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateCountyTable extends Migration {

	/**
	 * Run the migrations. Creates county table
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('county', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->integer('country')->unsigned();
        });
        
        Schema::table('county', function (Blueprint $table) {
           $table->foreign('country')->references('id')->on('country'); 
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropForeign('county_country_foreign');
        Schema::drop('county');
	}

}
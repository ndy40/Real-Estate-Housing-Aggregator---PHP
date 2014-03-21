<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePostCodeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('post_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->integer('county')->unsigned();
        });
        
        Schema::table('post_codes', function (Blueprint $table) {
           $table->foreign('county')->references('id')->on('county'); 
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('post_codes', function (Blueprint $table) {
            $table->dropForeing('post_codes_county_foreign');
        });
        
        Schema::drop('post_codes');
	}

}

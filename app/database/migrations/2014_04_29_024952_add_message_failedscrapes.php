<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessageFailedscrapes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table("failed_scrapes", function (Blueprint $table){
           $table->string("message"); 
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table("failed_scrapes", function (Blueprint $table){
           $table->dropColumn("message"); 
        });
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataColumnFailedScrapes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::table("failed_scrapes", function (Blueprint $table) {
            $table->text("data");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table("failed_scrapes", function (Blueprint $table) {
            $table->dropColumn("failed_scrapes");
        });
	}

}

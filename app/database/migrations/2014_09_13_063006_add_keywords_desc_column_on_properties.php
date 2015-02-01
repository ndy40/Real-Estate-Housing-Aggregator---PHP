<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Add Keywords and Description column to Properties table.
 */
class AddKeywordsDescColumnOnProperties extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table("properties", function (Blueprint $table) {
            $table->string("keywords")->nullable();
            $table->text("description")->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table("properties", function (Blueprint $table) {
            $table->dropColumn("keywords", "description");
        });
	}

}

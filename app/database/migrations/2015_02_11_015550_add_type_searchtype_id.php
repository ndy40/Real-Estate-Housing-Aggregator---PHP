<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeSearchtypeId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("type", function (Blueprint $table) {
            $table->foreign('search_type_id')->references('id')->on('property_search_type');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('type', function (Blueprint $table) {
            $table->dropForeign('type_search_type_foreign');
        });
	}

}

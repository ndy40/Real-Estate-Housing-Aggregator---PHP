<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOffertype extends Migration {

	/**
	 * Run the migrations.
     *
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table("properties", function (Blueprint $table) {
            $table->enum("offer_type", array("Rent", "Sale"));
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
            $table->dropColumn("offer_type");
        });
	}

}

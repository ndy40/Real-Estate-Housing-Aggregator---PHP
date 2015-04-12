<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColCountyAvg extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table("county", function (Blueprint $table) {
            $table->double("avg_sale", 15, 2)->nullable()->default(0);
            $table->double("avg_rent", 15, 2)->nullable()->default(0);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table("county", function (Blueprint $table) {
            $table->dropColumn("avg_sale");
            $table->dropColumn("avg_rent");
        });
	}

}

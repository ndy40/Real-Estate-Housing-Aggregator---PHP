<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePropertyChangelog extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create("property_change_logs", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("property_id")->unsigned();
            $table->double("old_price");
            $table->double("new_price");
            $table->timestamps();
        });

        Schema::table("property_change_logs", function (Blueprint $table) {
           $table->foreign("property_id")->references("id")->on("properties");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop("property_changelog");
	}

}

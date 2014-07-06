<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdateFieldPostcode extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table("post_codes", function (Blueprint $table) {
            $table->timestamp("created_at");
            $table->timestamp("updated_at");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table("post_codes", function (Blueprint $table){
            $table->dropColumns("created_at", "updated_at");
        });
	}

}

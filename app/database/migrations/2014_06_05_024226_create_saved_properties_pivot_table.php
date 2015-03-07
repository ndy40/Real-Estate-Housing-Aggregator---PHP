<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateSavedPropertiesPivotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("saved_properties", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->integer("user_id")->unsigned();
            $table->bigInteger("property_id")->unsigned();
            $table->string("calculations");
        });

        Schema::table("saved_properties", function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table("saved_properties", function (Blueprint $table) {
            $table->dropForeign("saved_properties_user_id_foreign");
            $table->dropForeign("saved_properties_property_id_foreign");
        });

        Schema::drop("saved_properties");
	}

}

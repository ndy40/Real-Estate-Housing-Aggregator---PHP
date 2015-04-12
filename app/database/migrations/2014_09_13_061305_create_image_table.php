<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateImageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create("images", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("property_id")->unsigned();
            $table->string("image");
            $table->string("thumb");
            $table->integer("size")->nullable();
            $table->integer("thumb_size")->nullable();
            $table->boolean("processed")->default(false);
            $table->boolean("enabled")->default(true);
        });
        
        Schema::table("images", function (Blueprint $table) {
            $table->foreign("property_id")
                ->references("id")
                ->on("properties")
                ->onDelete("cascade");
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table("images", function (Blueprint $table) {
            $table->dropForeign("property_id_foreign");
        });
        
        Schema::drop("images");
	}

}

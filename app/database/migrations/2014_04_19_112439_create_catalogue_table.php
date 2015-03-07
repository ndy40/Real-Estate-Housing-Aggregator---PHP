<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateCatalogueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create("catalogue", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("agency_id")->unsigned();
            $table->text("url");
        });
        
        Schema::table("catalogue", function (Blueprint $table){
            $table->foreign("agency_id")->references("id")->on("agency")
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
        Schema::table("catalogue", function (Blueprint $table){
            $table->dropForeign("catalogue_agency_id_foreign");
        });
        
        Schema::drop("catalogue");  
	}

}

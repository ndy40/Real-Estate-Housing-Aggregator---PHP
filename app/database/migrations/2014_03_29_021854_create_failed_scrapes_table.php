<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateFailedScrapesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('failed_scrapes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agency_id')->unsigned();
            $table->timestamps();
            $table->text('results');
            $table->softDeletes();
        });
        
        Schema::table('failed_scrapes', function (Blueprint $table) {
            $table->foreign('agency_id')->references('id')->on('agency')
                ->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropForeign('failed_scrapes_agency_id_foreign');
        Schema::drop('failed_scrapes');
	}

}

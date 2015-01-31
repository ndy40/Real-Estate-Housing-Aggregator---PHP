<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('agency_id')->unsigned();
            $table->string('marketer');
            $table->string('phone')->nullable();
            $table->integer('type_id')->unsigned()->nullable();
            $table->integer('rooms')->nullable();
            $table->string('address');
            $table->integer('post_code_id')->unsigned();
            $table->mediumText('url');
            $table->float('price');
            $table->string('hash')->nullable();
            $table->boolean('available')->default(true);
            $table->boolean('published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::table('properties', function (Blueprint $table) {
           $table->foreign('agency_id')->references('id')->on('agency');
           $table->foreign('type_id')->references('id')->on('type');
           $table->foreign('post_code_id')->references('id')->on('post_codes');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign('properties_agency_foreign');
            $table->dropForeign('properties_type_foreign');
            $table->dropForeign('properties_post_code_foreign');
        });
        
        Schema::drop('properties');
	}

}

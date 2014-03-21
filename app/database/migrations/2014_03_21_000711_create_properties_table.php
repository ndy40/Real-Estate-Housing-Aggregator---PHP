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
            $table->integer('agency')->unsigned();
            $table->string('reference');
            $table->integer('type')->unsigned();
            $table->integer('rooms')->nullable();
            $table->string('address');
            $table->integer('post_code')->unsigned();
            $table->mediumText('url');
            $table->float('price');
            $table->string('hash')->nullable();
            $table->boolean('available')->default(true);
            $table->boolean('published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::table('properties', function (Blueprint $table) {
           $table->foreign('agency')->references('id')->on('agency');
           $table->foreign('type')->references('id')->on('type');
           $table->foreign('post_code')->references('id')->on('post_codes');
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

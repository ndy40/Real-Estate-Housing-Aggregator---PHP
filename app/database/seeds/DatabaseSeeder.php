<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

       	$this->call('CountryTableSeeder');
       	$this->call('PropertyTableSeeder');
       	$this->call('PostCodeTableSeeder');
        $this->call('GroupTableSeeder');
        
	}

}

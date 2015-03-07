<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CountryTableSeeder
 *
 * @author ndy40
 */
class CountryTableSeeder extends Seeder
{
    public function run ()
    {
        $this->command->info("Seeding country table");
        $id = DB::table('country')->insertGetId(
            array(
                'name' => 'Great Britain',
                'code' => 'gb', 
                'currency' => 'GBP',
                'enabled' => 1
            )
        );
        
        DB::table('agency')->insert(
            array(
                'name' => "Zooplar",
                'crawler' => 'zoopla',
                'country_id' => $id,
                'enabled' => 1
            )
        );
    }
}

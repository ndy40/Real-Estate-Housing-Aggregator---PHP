<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PropertyTypeSeed
 *
 * @author ndy40
 */
class PropertyTypeSeed extends Seeder
{
    public function run()
    {
        $this->command->info("Seeding property types.");
        $type = array("Houses", "Flats", "Commercial", "Land",
            "Bungalows", "Detached", "Terraced", "Semi Detached", "Apartment", "Maisonette"
        );
        foreach ($type as $value) {
            DB::table("type")->insert(array("name" => $value));
        }
        
        
    }
}

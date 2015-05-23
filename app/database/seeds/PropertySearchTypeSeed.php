<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PropertySearchTypeSeed
 *
 * @author kaimin
 */
class PropertySearchTypeSeed extends Seeder
{
    public function run()
    {
        $this->command->info("Seeding property search types.");
        $type = array("Not Supported","Houses", "Flats / Apartments", "Land", "Mobile / Park Homes",
            "Character Property", "Garage / Parking", "Bungalows", "Retirement Property", "House / Flat Share", "Commercial Property"
        );
        foreach ($type as $value) {
            DB::table("property_search_type")->insert(array("name" => $value));
        }


    }
}
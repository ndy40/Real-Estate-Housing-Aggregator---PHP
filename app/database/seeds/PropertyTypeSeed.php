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
        $type = array(1 => array("Not Supported"),
            array("Terraced", "End of Terrace", "Semi-Detached", "Detached", "Mews", "Cluster House", "Link Detached House",
                "Town House", "Cottage", "Chalet", "Villa", "Finca", "Village House", "Semi-detached Villa", "Detached Villa", ),
            array("Ground Flat", "Flat", "Studio", "Ground Maisonette", "Maisonette", "Apartment", "Penthouse", "Serviced Apartments",
                "Duplex", "Triplex", "Hotel Room", "Block of Apartments", "Private Halls", "Coach House"),
            array("Land", "Farm Land", "Plot", "Off-Plan", "Smallholding"),
            array("Mobile Home", "Park Home", "Caravan"),
            array("Barn Conversion", "Farm House", "Equestrian Facility", "Longere", "Gite", "Barn", "Trulli", "Mill", "Ruins", "Castle",
                "Cave House", "Cortijo", "Country House", "Stone House", "Lodge", "Log Cabin", "Manor House", "Stately Home", "Riad", "House Boat"),
            array("Parking", "Garages"),
            array("Bungalow", "Terraced Bungalow", "Semi-Detached Bungalow", "Detached Bungalow"),
            array("Sheltered Housing", "Retirement Property"),
            array("House Share", "Flat Share"),
            array("Restaurant", "Cafe", "Bar / Nightclub", "Shop", "Office", "Business Park", "Serviced Office", "Retail Property (high street)",
                "Retail Property (out of town)", "Convenience Store", "Garage", "Hairdresser / Barber Shop", "Hotel", "Petrol Station", "Post Office",
                "Pub", "Workshop", "Distribution Warehouse", "Factory", "Heavy Industrial", "Industrial Park", "Light Industrial", "Storage", "Showroom",
                "Warehouse", "Commercial Development", "Industrial Development", "Residential Development", "Commercial Property", "Data Centre",
                "Farm", "Healthcare Facility", "Marine Property", "Mixed Use", "Research & Development Facility", "Science Park", "Guest House",
                "Hospitality", "Leisure Facility", "Takeaway", "Childcare Facility", "Place of Worship", "Trade Counter")
        );
        foreach ($type as $key => $values) {
            foreach ($values as $value) {
                DB::table("type")->insert(array(array("name" => $value, "search_type_id" => $key)));
            }
        }
        
    }
}

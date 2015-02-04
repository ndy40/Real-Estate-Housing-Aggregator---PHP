<?php
/**
 * This file contains all the possible configuration parameters for a crawler.
 */
return array (
    // Name of the scripting command to be used. Could be swaped for PhantomJS if required.
    'command' => 'casperjs',
    
    //name of the script to use for initiating crawls.
    "script_name" => base_path() . "/crawlers/crunch.js",

    "config_file" => base_path() . "/crawlers/config.json",
    
    "output_path" => storage_path() . "/scrape",
    
    "crawler_dir" => base_path() . "crawlers",
    
    "image_full"  => array ("width" => 653, "height" => 453),
    
    "image_thumb" => array ("width" => 155, "height" => 103),
    
    "image_thumb_quality" => 0,
    
    "image_full_quality"  => 45,
    
    "image_dir" => "/assets/properties/full",
    
    "image_thumb_dir" =>  "/assets/properties/thumb",
    
    "image_filename_template" => "%d_%d_%s.jpg", // property ID_Serial_Number_timestamp
    
    "tor_port" => "localhost:9050",

    "dataexport_upload_path" => storage_path() . "/data/dataexport/uploads",
    
);


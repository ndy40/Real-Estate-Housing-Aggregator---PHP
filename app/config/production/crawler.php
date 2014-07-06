<?php
/**
 * This file contains all the possible configuration parameters for a crawler.
 */
return array (
    // Name of the scripting command to be used. Could be swaped for PhantomJS if required.
    'command' => 'casperjs',
    
    //name of the script to use for initiating crawls.
    "script_name" => "/home/ndy40/www/crawler/crunch.js",
    
    "config_file" => "/home/ndy40/www/crawler/config.json",
    
    "crawler_list_schema" => "somefile.xsd",
    
    "cralwer_item_schema" => 'somefile.xsd',
    
    "output_path" => storage_path() . "/scrape",

    "property_regex" => "/house|semi-detached|"
    
    
    
);


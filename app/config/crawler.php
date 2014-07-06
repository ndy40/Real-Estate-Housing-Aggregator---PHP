<?php
/**
 * This file contains all the possible configuration parameters for a crawler.
 */
return array (
    // Name of the scripting command to be used. Could be swaped for PhantomJS if required.
    'command' => 'casperjs',
    
    //name of the script to use for initiating crawls.
    "script_name" => app_path() . "/crawlers/crunch.js",
    
    "config_file" => app_path() . "/crawlers/config.json",
    
    "output_path" => storage_path() . "/scrape",
    
    "crawler_dir" => app_path() . "crawlers",
    
);


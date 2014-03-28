<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\crawler;

use DOMDocument;
use models\exceptions\EmptyItemException;

/**
 * Description of JobQueue
 *
 * @author ndy40
 */
class JobQueue {
    
    public function fetchDetails ($job, $data) {
        echo "Picking up new job." . $job->getJobId() . PHP_EOL;
        echo "Job parameters:\nCountry:\t" . $data['country'] . "\nAgency:\t"
            . $data['agency'] . "\n";
        $fileContent = file_get_contents($data['results']);
        $doc = new DOMDocument;
        $doc->loadXML($fileContent);
        
        $items = $doc->getElementsByTagName("item");
        if (is_null($items)) {
            throw new EmptyItemException ('No item found in result');
            //we should log this occurrence for further investigation.
        }
        
        
        echo "Finished processing job";
        $job->delete();
    }
    
    public function itemDetails ($job, $data) {
        echo "Picking up new Job " . $job->getJobId() . PHP_EOL;
        print_r($data);
        echo "Finishing process job" . PHP_EOL;
        $job->delete();
        
    }
    
}

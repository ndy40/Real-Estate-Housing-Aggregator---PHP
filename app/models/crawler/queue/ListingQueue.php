<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\crawler\queue;

use models\crawler\abstracts\JobQueue;
use DOMDocument;
use models\exceptions\EmptyItemException;
use Illuminate\Support\Facades\Artisan;

/**
 * Description of FetchQueue
 *
 * @author ndy40
 */
class ListingQueue extends JobQueue
{   
    public function fire($job, $data) 
    {
        echo "Picking up new job." . $job->getJobId() . PHP_EOL;
        echo "Job parameters:\n------------\nCountry\t" 
            . $data['country'] . "\nAgency:\t"
            . $data['agent'] . "\n";
        $fileContent = file_get_contents($data['result']);
        $doc = new DOMDocument;
        $doc->loadXML($fileContent);
        //Validate xml to ensure it meets XSD standard.
        //TODO: Add settings to XSD file for validation

        if ($job->attempts() > 5) {
            $job->bury();
            return;
        }

        $items = $doc->getElementsByTagName("item");
        if (is_null($items)) {
            throw new EmptyItemException('No item found in result');
            $job->burry();
            return;
            //we should log this occurrence for further investigation.
        }

        $job->delete();

        $numberOfItems = 0;

        foreach ($items as $item) {
            //get the url
            $url = $item->getElementsByTagName('url')->item(0);
            Artisan::call(
                'crunch:item',
                array(
                    'country' => $data['country'],
                    'agent'   => $data['agent'],
                    'url'     => rawurldecode($url->nodeValue),
                )
            );

            $numberOfItems++; //increment for each items processed.
        }
        unlink($data['result']);
        //do some job logging.
        echo "Finished processing job";
    }

}

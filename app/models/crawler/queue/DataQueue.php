<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\crawler\queue;

use models\crawler\abstracts\JobQueue;
use DOMDocument;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use models\exceptions\EmptyItemException;
use models\entities\FailedScrapes;

/**
 * Description of FetchQueue
 *
 * @author kaimin
 */
class DataQueue extends JobQueue
{
	
    public function fire($job, $data) 
    {
        echo "Picking up new job." . $job->getJobId() . PHP_EOL;
        echo "Job parameters:\n------------\nCountry\t"
            . $data['country'] . "\nAgency:\t"
            . $data['agent'] . "\n";
        $fileContent = file_get_contents($data['result']);
        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML($fileContent);
		unlink($data['result']);
        //Validate xml to ensure it meets XSD standard.
        //TODO: Add settings to XSD file for validation

//        if ($job->attempts() > 5) {
//            $job->bury();
//            return;
//        }

        $properties = $doc->getElementsByTagName("item");
        if (is_null($properties)) {
            throw new EmptyItemException('No item found in result');
            $job->bury();
            return;
            //we should log this occurrence for further investigation.
        }

        $numberOfItems = 0;

        foreach ($properties as $property) {
            //get the url
            $dom = new DOMDocument('1.0', 'utf-8');
            $node = $dom->importNode($property, true);

			$country_ele = $dom->createElement('country', $data['country']);
			$agent_ele = $dom->createElement('agent', $data['agent']);
			$node->appendChild($country_ele);
			$node->appendChild($agent_ele);
			$dom->appendChild($node);

            $feedRespository = App::make('FeedRepository');
	
			$feedRespository->saveProperty($dom);

            $numberOfItems++; //increment for each items processed.
        }
		$job->delete();
        //do some job logging.
        echo "Finished processing job";
    }

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\crawler;

use DOMDocument;
use models\exceptions\EmptyItemException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\App;
use models\entities\PostCode;



/**
 * Description of JobQueue
 *
 * @author ndy40
 */
class JobQueue {
    
    const ITEM_NOT_AVAILABLE = 'notAvailable';
    const ITEM_AVAILABLE = 'available';
    const ITEM_REMOVED = 'removed';
    
    public function fetchDetails ($job, $data)
    {
        echo "Picking up new job." . $job->getJobId() . PHP_EOL;
        echo "Job parameters:\nCountry:\t" . $data['country'] . "\nAgency:\t"
            . $data['agent'] . "\n";
        $fileContent = file_get_contents($data['result']);
        $doc = new DOMDocument;
        $doc->loadXML($fileContent);
        //Validate xml to ensure it meets XSD standard.
        //TODO: Add settings to XSD file for validation

        $items = $doc->getElementsByTagName("item");
        if (is_null($items)) {
            throw new EmptyItemException ('No item found in result');
            //we should log this occurrence for further investigation.
        }

        $numberOfItems = 0;

        foreach($items as $item) {
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

        //do some job logging.

        echo "Finished processing job";
        $job->delete();
    }

    public function itemDetails ($job, $data)
    {
        $scrapeRespository = App::make('ScrapeRepository');
        $propertyRepo = App::make('PropertyRepository');
        
        echo "Picking up new Job " . $job->getJobId() . PHP_EOL;
        echo "Country:\t" . $data['country'] . "\nAgent:\t" . $data['agent'];
        $doc = new \DOMDocument;
        $doc->loadXML(file_get_contents($data['result']));
        $errorMessages = $doc->createElement('error');
        $hasErrors = false;

        $status = $doc->getElementsByTagName('status')->item(0)->nodeValue;
        
        if ($status !== self::ITEM_REMOVED) {
            //check each field to see if things are okay.
            $url = $doc->getElementsByTagName('url')->item(0);
            if (is_null($url)
                || !isset($url->nodeValue)
                || $url->nodeValue === 'undefined'
            ){
                $errorMessages->appendChild(new \DOMText('No url provided.'));
                $hasErrors = true;
            }

            $address = $doc->getElementsByTagName('address');
            if (is_null($address)
                || !isset($address->nodeValue)
                || $address->nodeValue === 'undefined'
            ){
                $errorMessages->appendChild(new \DOMText('No address found'));
                $hasErrors = true;
            }

            $areaCode = $doc->getElementsByTagName('areacode');
            if (is_null($areaCode)
                || !isset($areaCode->nodeValue)
                || $areaCode->nodeValue === 'undefined'
            ){
                $errorMessages->appendChild(new \DOMText('No area code found'));
                $hasErrors = true;
            } elseif(!$propertyRepo->fetchPostCode($areaCode)) {
                $errorMessages->appendChild(new \DOMText('Post code not in DB'));
                $hasErrors = true;
            }

            $roomType = $doc->getElementsByTagName('type');
            if (is_null($roomType)
                || !isset($roomType->nodeValue)
                || $roomType->nodeValue === 'undefined'
            ){
                $errorMessages->appendChild(new \DOMText('Room type not specified'));
                $hasErrors = true;
            }

            if ($hasErrors) {
                $doc->appendChild($errorMessages);
                $scrapeRespository->saveFailedScrapes(
                    $data['agent'],
                    $data['country'],
                    $data['results']
                );
                $job->release();
            } else {
                $scrapeRespository->saveProperty($doc);
                $job->delete();
            }
            
        } else {
            Queue::push('JobQueue@remove', $data);
            $job->delete();
        }
        echo "Finishing process job" . PHP_EOL;
        
    }

}

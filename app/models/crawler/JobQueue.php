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
    const ITEM_PROPERTY_NOT_SUPPORTED = 'notSupported';
    
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
        
        if ($job->attempts() > 3) {
            $job->delete();
            return;
        }
        
        $items = $doc->getElementsByTagName("item");
        if (is_null($items)) {
            throw new EmptyItemException ('No item found in result');
            $job->delete();
            return;
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
        unlink($data['result']);
        $job->delete();
    }

    public function itemDetails ($job, $data)
    {
        $scrapeRespository = App::make('ScrapeRepository');

        $propertyRepo = App::make('PropertyRepository');
        
        echo "Picking up new Job " . $job->getJobId() . PHP_EOL;

        echo "Country:\t" . $data['country'] . "\nAgent:\t" . $data['agency'] . PHP_EOL;

        try {
            $doc = new \DOMDocument;
            $doc->loadXML(file_get_contents($data['result']));
        }catch (\ErrorException $ex) {
            $job->delete();
        }
        $errorMessages = $doc->createElement('error');

        $hasErrors = false;

        //check if property type is supported
        $propertyType = $doc->getElementsByTagName("type")->item(0)->nodeValue;
        if ($propertyType === JobQueue::ITEM_PROPERTY_NOT_SUPPORTED) {
            $job->delete();
            unlink($data['result']);
            return;
        }

        $status = $doc->getElementsByTagName('status')->item(0)->nodeValue;
        $messages = array();
        
        if ($status !== self::ITEM_REMOVED) { 
            //check each field to see if things are okay.
            $url = $doc->getElementsByTagName('url')->item(0);
            if (is_null($url)
                || !isset($url->nodeValue)
                || $url->nodeValue === 'undefined'
            ){
                $messages[] = 'No url provided.';
                $errorMessages->appendChild(new \DOMText('No url provided.'));
                $hasErrors = true;
            }

            $address = $doc->getElementsByTagName('address')->item(0);
            if (is_null($address)
                || !isset($address->nodeValue)
                || $address->nodeValue === 'undefined'
            ){
                $messages[] = 'No address found';
                $errorMessages->appendChild(new \DOMText('No address found'));
                $hasErrors = true;
            }

            $areaCode = $doc->getElementsByTagName('areacode')->item(0);
            if (is_null($areaCode)
                || !isset($areaCode->nodeValue)
                || $areaCode->nodeValue === 'undefined'
            ){
                $messages[] = 'No area code found';
                $errorMessages->appendChild(new \DOMText('No area code found'));
                $hasErrors = true;
            } elseif(!$propertyRepo->fetchPostCode($areaCode->nodeValue)) {
                $messages[] = "Post code not in DB";
                $errorMessages->appendChild(new \DOMText('Post code not in DB'));
                $hasErrors = true;
            }

            $offerType = $doc->getElementsByTagName("offertype")->item(0);
            if (is_null($offerType)
                || !isset($offerType->nodeValue)
                || $offerType->nodeValue == 'undefined')
            {
                $messages[] = "Offer type not specified";
                $errorMessages->appendChild(new \DOMText("Offer type not specified"));
                $hasErrors = true;
            }

            $roomType = $doc->getElementsByTagName('type')->item(0);
            if (is_null($roomType)
                || !isset($roomType->nodeValue)
                || $roomType->nodeValue === 'undefined'
            ){
                $messages[] = 'Room type not specified';
                $errorMessages->appendChild(new \DOMText('Room type not specified'));
                $hasErrors = true;
            }

            if ($hasErrors) {
                $doc->appendChild($errorMessages);
                $scrapeRespository->saveFailedScrapes(
                    $data['agency'],
                    $data['country'],
                    $data['result'],
                    implode("\n", $messages),
                    $fileContent
                );
                $job->delete();

            } else {
                $scrapeRespository->saveProperty($doc);
                $job->delete();
                unlink($data['result']);
            }
            
        } else {
            $job->delete();
            unlink($data['result']);
        }

        echo "Finishing process job" . PHP_EOL;
        
    }

}

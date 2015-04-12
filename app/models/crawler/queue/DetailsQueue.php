<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\crawler\queue;

use models\crawler\abstracts\JobQueue;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use models\entities\FailedScrapes;

/**
 * Description of DetailsQueue
 *
 * @author ndy40
 */
class DetailsQueue extends JobQueue 
{
    public function fire($job, $data) 
    {
        $scrapeRespository = App::make('ScrapeRepository');

        $propertyRepo = App::make('PropertyRepository');

        Log::info(sprintf("Picking new Job %d", $job->getJobId()));

        echo "Country:\t" . $data['country'] . "\nAgent:\t" . $data['agency'] 
            . PHP_EOL;

        try {
            $doc = new \DOMDocument;
            $doc->loadXML($data['result']);
        } catch (\ErrorException $ex) {
            $job->bury();
        }
       
        $errorMessages = $doc->createElement('error');
       
        $hasErrors = false;

        //check if property type is supported
        $propertyType = $doc->getElementsByTagName("type")->item(0)->nodeValue;
        
        if ($propertyType === JobQueue::ITEM_PROPERTY_NOT_SUPPORTED) {
            $job->delete();
            return;
        }

        $status = $doc->getElementsByTagName('status')->item(0)->nodeValue;
        $messages = array();

        if ($status !== self::ITEM_REMOVED) {
            //check each field to see if things are okay.
            $url = $doc->getElementsByTagName('url');
            if (is_null($url)
                || !isset($url->item(0)->nodeValue)
                || $url->item(0)->nodeValue === 'undefined'
            ) {
                $messages[] = 'No url provided.';
                $errorMessages->appendChild(new \DOMText('No url provided.'));
                $hasErrors = true;
            }

            //check each field to see if things are okay.
            $price = $doc->getElementsByTagName('price');
            if (is_null($price)
                || !isset($price->item(0)->nodeValue)
                || $price->item(0)->nodeValue === 'undefined'
            ) {
                $messages[] = 'Price for not available.';
                $errorMessages->appendChild(
                    new \DOMText('Price for not available.')
                );
                $hasErrors = true;
            }

            $address = $doc->getElementsByTagName('address');
            if (is_null($address)
                || !isset($address->item(0)->nodeValue)
                || $address->item(0)->nodeValue === 'undefined'
            ) {
                $messages[] = 'No address found';
                $errorMessages->appendChild(new \DOMText('No address found'));
                $hasErrors = true;
            }

            $areaCode = $doc->getElementsByTagName('areacode');
            if (is_null($areaCode)
                || !isset($areaCode->item(0)->nodeValue)
                || $areaCode->item(0)->nodeValue === 'undefined'
            ) {
                $messages[] = 'No area code found';
                $errorMessages->appendChild(new \DOMText('No area code found'));
                $hasErrors = true;
            } elseif (!$propertyRepo->fetchPostCode($areaCode->item(0)->nodeValue)) {
                $messages[] = "Post code not in DB";
                $errorMessages->appendChild(new \DOMText('Post code not in DB'));
                $hasErrors = true;
            }

            $offerType = $doc->getElementsByTagName("offertype");
            if (is_null($offerType)
                || !isset($offerType->item(0)->nodeValue)
                || $offerType->item(0)->nodeValue == 'undefined'
            ) {
                $messages[] = "Offer type not specified";
                $errorMessages->appendChild(
                    new \DOMText("Offer type not specified")
                );
                $hasErrors = true;
            }

            $roomType = $doc->getElementsByTagName('type');
            if (is_null($roomType)
                || !isset($roomType->item(0)->nodeValue)
                || $roomType->item(0)->nodeValue === 'undefined'
            ) {
                $messages[] = 'Room type not specified';
                $errorMessages->appendChild(new \DOMText('Room type not specified'));
                $hasErrors = true;
            }

            if ($hasErrors) {
                $doc->appendChild($errorMessages);
                $failedScrapes = new FailedScrapes(array(
                    "results" => $data['result'],
                    "message" => implode("\n", $messages)
                ));
                $scrapeRespository->saveFailedScrapes(
                    $data['agency'],
                    $data['country'],
                    $failedScrapes
                );
                $job->bury();
            } else {
                $scrapeRespository->saveProperty($doc);
                $job->delete();
            }
        } else {
            $job->delete();
        }
        echo "Finishing process job" . PHP_EOL;
    }

//put your code here
}

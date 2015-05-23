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
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Description of DetailsQueue
 *
 * @author ndy40
 */
class DetailsQueue extends JobQueue
{

    const PROPERTY_SAVE_FAILED = 'ERROR_IN_PROPERTY';

    const PROPERTY_SAVED = "SAVED";

    const PROPERTY_IGNORED = "IGNORED";

    private $scrapeRespository;

    private $propertyRepo;

    public function fire($job, $data)
    {
        $this->scrapeRespository = App::make('ScrapeRepository');

        $this->propertyRepo = App::make('PropertyRepository');

        try {
            $doc = new \DOMDocument;
            $doc->loadXML(file_get_contents($data['result']));
        } catch (\ErrorException $ex) {
            $job->bury();
        }

        $properties = $doc->getElementsByTagName("property");

        foreach ($properties as $property) {
            //check if property type is supported
            $newDoc = new \DOMDocument('1.0', 'utf-8');
            $property = $newDoc->importNode($property, true);
            $newDoc->appendChild($property);
            $propertyType = $property->getElementsByTagName("type")->item(0)->nodeValue;

            if ($propertyType === JobQueue::ITEM_PROPERTY_NOT_SUPPORTED) {
                continue;
            }

            $country_ele = $newDoc->createElement("country", $data["country"]);
            $newDoc->appendChild($country_ele);

            $agent = $newDoc->createElement("agent", $data["agent"]);
            $newDoc->appendChild($agent);

            $status = $property->getElementsByTagName('status')->item(0)->nodeValue;

            if ($status !== self::ITEM_REMOVED) {
                $this->processProperty($newDoc, $data);
            }

        }

        $job->delete();
    }


    public function processProperty($doc, $data)
    {
        $hasErrors = false;
        $messages = array();
        $errorMessages = $doc->createElement('error');

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
        } elseif (!$this->propertyRepo->fetchPostCode($areaCode->item(0)->nodeValue)) {
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
                "results" => $doc->saveXML(),
                "message" => implode("\n", $messages)
            ));
            $this->scrapeRespository->saveFailedScrapes(
                $data['agent'],
                $data['country'],
                $failedScrapes
            );
            return self::PROPERTY_SAVE_FAILED;
        } else {
            $this->scrapeRespository->saveProperty($doc);
        }

        return self::PROPERTY_SAVED;
    }

//put your code here
}

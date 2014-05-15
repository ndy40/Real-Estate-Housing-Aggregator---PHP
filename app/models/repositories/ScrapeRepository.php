<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\repositories;

use models\interfaces\RepositoryInterface;
use Illuminate\Support\Facades\App;
use models\entities\FailedScrapes;
use models\crawler\JobQueue;
use models\entities\Property;
use models\entities\PostCode;
use Exception;

/**
 * Description of ScrapeRepository
 *
 * @author ndy40
 */
class ScrapeRepository implements RepositoryInterface
{
    protected $propertyRepo;
    protected $agentRepo;

    public function __construct() {
        $this->propertyRepo = $propertyRepo =  App::make('PropertyRepository');
        $this->agentRepo = App::make('AgentRespository');
    }


    public function delete($id) {

    }

    public function fetch($id) {

    }

    public function update($entity) {

    }

    /**
     * This saves failed scrape jobs into the
     * @param string $country - Country code
     * @param string $agent - Agency name.
     * @param mixed[] $data - Scrape job data
     */
    public function saveFailedScrapes($agent, $country, $result, $messages = array(), $data = null )
    {
        $agency = $this->agentRepo->fetchAgentByNameAndCountry(
                $agent,
                $country
        );
        $failedJob = new FailedScrapes(array(
            "results" => $result,
            "message" => $messages,
            "data"    => $data
        ));
        $agency->failedScrapes()->save($failedJob);

        return $failedJob;
    }

    public function saveProperty(\DOMDocument $data)
    {
        $scrapedProperty = $this->buildPropertyClassPart($data);

        $status = $data->getElementsByTagName('status')->item(0)->nodeValue;
        $agent = $data->getElementsByTagName('agent')->item(0)->nodeValue;
        $country = $data->getElementsByTagName('country')->item(0)->nodeValue;

        $type = $data->getElementsByTagName('type')->item(0)->nodeValue;
        $type = $this->propertyRepo->fetchPropertyType($type);
        $scrapedProperty->type()->associate($type);

        $postCode = $data->getElementsByTagName('areacode')->item(0)->nodeValue;

        $agency = $this->agentRepo->fetchAgentByNameAndCountry($agent, $country);
        $scrapedProperty->agency()->associate($agency);


        if (is_null($postCode)) {
            $postCode = $data->getElementsByTagName('areacode')->item(0)->nodeValue;
            $postCode = new PostCode(array('code' => strtoupper($postCode)));
            $postCode->save();
        }

        $postCode = $this->propertyRepo->fetchPostCode($postCode);
        $scrapedProperty->postCode()->associate($postCode);

        switch ($status) {
            case JobQueue::ITEM_NOT_AVAILABLE :
                $property = $this->propertyRepo
                    ->fetchPropertyByHash($scrapedProperty->hash);

                if (!is_null($property)) {
                    $property->available = false;
                    $property->save();
                }
                break;
            case JobQueue::ITEM_AVAILABLE:
                $scrapedProperty->available = 1;
                $property = $this->propertyRepo
                    ->fetchPropertyByHash($scrapedProperty->hash);
                //check if agency has been configured for auto-approval. If true then
                //set available to 1.
                if ($agency->auto_publish) {
                    $scrapedProperty->published = 1;
                }
                if ($property === null) {
                   $scrapedProperty =  $this->propertyRepo->save($scrapedProperty);
                } else {
                    //perform proper test on data insert/update of property.
                    $scrapedProperty = $this->propertyRepo
                        ->updateChangedFields($scrapedProperty, $property);
                    $scrapedProperty = $this->propertyRepo->save($scrapedProperty);
                }
                break;
        }

        return $scrapedProperty;
    }

    protected function buildPropertyClassPart(\DOMDocument $data)
    {
        $scrapeProperty = array();
        $country = rawurldecode($data->getElementsByTagName('country')->item(0)->nodeValue);
        $agent = rawurldecode($data->getElementsByTagName('agent')->item(0)->nodeValue);

        $marketer = $data->getElementsByTagName('marketer');
        if ($marketer) {
            $scrapeProperty['marketer'] = $marketer->item(0)->nodeValue;
        }

        $address = $data->getElementsByTagName('address');
        $scrapeProperty['address'] = $address->item(0)->nodeValue;

        $room = $data->getElementsByTagName('rooms');
        if (!is_null($room->item(0))) {
            $scrapeProperty['rooms'] = $room->item(0)->nodeValue;
        }

        $postcode = $data->getElementsByTagName('areacode')->item(0)->nodeValue;

        $scrapeProperty['phone'] = $data->getElementsByTagName('phone')->item(0)->nodeValue;

        $scrapeProperty['price'] = doubleval($data->getElementsByTagName('price')->item(0)->nodeValue);

        $scrapeProperty["offer_type"] = $data->getElementsByTagName("offertype")->item(0)->nodeValue;

        $scrapeProperty['url'] = rawurldecode($data->getElementsByTagName('url')->item(0)->nodeValue);

        $scrapeProperty['hash'] = $this->propertyRepo->generatePropertyHash (
            $country,
            $agent,
            $scrapeProperty['address'],
            $scrapeProperty['marketer'],
            $postcode
        );
        $property = new Property();
        $property->assignAttributes($scrapeProperty);

        return $property;
    }

    public function save($entity) {
        throw new Exception ('Not implemented yet');
    }


    public function fetchAllFailedScrapes()
    {
        return FailedScrapes::orderBy("created_at", "asc")->get();
    }

    public function deleteFailedScrape($id)
    {
        $failedScrape = FailedScrapes::find($id);
        return $failedScrape->delete();
    }

}

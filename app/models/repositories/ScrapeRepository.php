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
    public function saveFailedScrapes($agent, $country, $result) 
    {
        $agency = $this->agentRepo->fetchAgentByNameAndCountry(
                $agent,
                $country
        );
        $failedJob = new FailedScrapes;
        $failedJob->results = $result;
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
        $postCode = $data->getElementsByTagName('areacode')->item(0)->nodeValue;
        
        $agency = $this->agentRepo->fetchAgentByNameAndCountry($agent, $country);
        $scrapedProperty->agency()->associate($agency);
        
        $postCode = $this->propertyRepo->fetchPostCode($postCode);
        if (is_null($postCode)) {
            $postCode = $data->getElementsByTagName('areacode')->item(0)->nodeValue;
            $postCode = new PostCode(array('code' => strtoupper($postCode)));
            $postCode->save();
        }
        $scrapedProperty->associate($postCode);
        
        $type = $this->propertyRepo->fetchPropertyType($type);
        $scrapedProperty->associate($type);
        
        switch ($status) {
            case JobQueue::ITEM_NOT_AVAILABLE :
                $property = $this->propertyRepo
                    ->fetchPropertyByHash($scrapedProperty->hash);
                $property->available = false;
                $property->save();
                break;
            case JobQueue::ITEM_AVAILABLE:
                $property = $this->propertyRepo
                    ->fetchPropertyByHash($scrapedProperty->hash);
                //check if agency has been configured for auto-approval. If true then 
                //set available to 1.
                if ($agency->auto_publish) {
                    $scrapedProperty->available = 1;
                }
                if (is_null($property)) {
                    $this->propertyRepo->save($scrapedProperty);
                } else {
                    //perform proper test on data insert/update of property.
                    $property = $this->propertyRepo
                        ->updateChangedFields($scrapedProperty, $property);
                    $property->save();
                }
                break;
        }
        return $property;
    }
    
    protected function buildPropertyClassPart(\DOMDocument $data)
    {
        $scrapeProperty = array();
        $country = rawurldecode($data->getElementsByTagName('country')->item(0)->nodeValue);
        $agent = rawurldecode($data->getElementsByTagName('agent')->item(0)->nodeValue);
        
        $scrapeProperty['marketer'] 
            = $data->getElementsByTagName('marketer')->item(0)->nodeValue;
        
        $scrapeProperty['address'] 
            = $data->getElementsByTagName('address')->item(0)->nodeValue;
        
        $scrapeProperty['rooms'] 
            = $data->getElementsByTagName('rooms')->item(0)->nodeValue;
        
        $postcode = $data->getElementsByTagName('rooms')->item(0)->nodeValue;
        
        $scrapeProperty['phone'] 
            = $data->getElementsByTagName('phone')->item(0)->nodeValue;
        
        $scrapeProperty['price'] 
            = doubleval($data->getElementsByTagName('price')->item(0)->nodeValue);
        
        $scrapeProperty['url'] = rawurldecode($data->getElementsByTagName('url')->item(0)->nodeValue);
        
        $scrapeProperty['hash'] = $this->propertyRepo->generatePropertyHash (
            $country,
            $agent,
            $scrapeProperty['address'],
            $scrapeProperty['marketer'],
            $postcode
        );
        
        return new Property($scrapeProperty);        
    }

    public function save($entity) {
        throw new Exception ('Not implemented yet');
    }

}

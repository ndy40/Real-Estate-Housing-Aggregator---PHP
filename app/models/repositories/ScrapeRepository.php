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


/**
 * Description of ScrapeRepository
 *
 * @author ndy40
 */
class ScrapeRepository implements RepositoryInterface
{
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
    public function saveFailedScrapes($agent, $country, $result) {
        $repo = App::make('AgentRespository');
        $agency = $repo->fetchAgentByNameAndCountry(
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
        $propertyRepo =  App::make('PropertyRepository');
        $agencyRepo = App::make('AgentRespository');
        $scrapeProperty = new \models\entities\Property;
        
        $status = $data->getElementsByTagName('status')->nodeValue;
        $marketer = $data->getElementsByTagName('marketer')->nodeValue;
        $scrapeProperty->setAttribute('marketer', $marketer);
        
        $address = $data->getElementsByTagName('address')->nodeValue;
        $scrapeProperty->setAttribute('address', $address);
        
        $rooms = (int)$data->getElementsByTagName('rooms')->nodeValue;
        $scrapeProperty->setAttribute('rooms',$rooms);
        
        $postCode = $data->getElementsByTagName('areacode')->nodeValue;
        $postCode = $propertyRepo->fetchPostCode($postCode);
        
        $phone = $data->getElementsByTagName('phone')->nodeValue;
        $price = doubleval($data->getElementsByTagName('price')->nodeValue);
        $type = $data->getElementsByTagName('type')->nodeValue;
        $url = $data->getElementsByTagName('url')->nodeValue;
        $country = $data->getElementsByTagName('country')->nodeValue;
        $agent = $data->getElementsByTagName('agent')->nodeValue;
        $hash = $propertyRepo->generatePropertyHash (
            $country,
            $agent,
            $address,
            $marketer,
            $postCode,
            $address
        );
        
        
        $agency = $agencyRepo->fetchAgentByNameAndCountry($agent, $country);
        switch ($status) {
            case JobQueue::ITEM_NOT_AVAILABLE :
                $property = $propertyRepo->fetchPropertyByHash($hash);
                
                
                
                break;
            case JobQueue::ITEM_AVAILABLE:
                
                break;
        }
       
    }
}

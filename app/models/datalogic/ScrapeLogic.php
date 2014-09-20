<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace models\datalogic;

use models\entities\FailedScrapes;
use models\interfaces\DataLogicInterface;
use models\interfaces\RepositoryInterface;
use models\interfaces\FactoryInterface;


/**
 * Description of ScrapeLogic
 *
 * @author ndy40
 */
class ScrapeLogic implements DataLogicInterface
{
    /**
     * Should contain an instance of the RepositoryInterface.
     * 
     * @var \models\interfaces\RepositoryInterface
     */
    protected $scrapeRepo;
    protected $agentRepo;
    protected $entityFactory;
    
    /**
     * Constructor for creating a ScrapeLogic object. This is use to insert and 
     * pick up scrape data.
     * 
     * @param \models\datalogic\RepositoryInterface $repository
     * @param \models\datalogic\AgentRepositoryInterface $agentRepository
     * @param \models\interfaces\EntityFactoryInterfaces $factory
     */
    public function __construct(
        RepositoryInterface $repository, 
        FactoryInterface $factory
    ){
        $this->scrapeRepo = $repository;
        $this->entityFactory = $factory;

    }
    /**
     * Fetch all failed scrapes from the database.
     * 
     * @return \models\entities\FailedScrapes[] An array of FailedScrape Objects.
     */
    public function fetchAllFailedScrapes ()
    {
        return $this->scrapeRepo->fetchAllFailedScrapes();
        
    }
    
    /**
     * Delete a failed scrape by Scrape ID. 
     * 
     * @param int $id
     * @return mixed
     */
    public function deleteFailedScrape($id)
    {
        return $this->scrapeRepo->deleteFailedScrape($id);
        
    }

    /**
     * Save a scrape that has failed.
     *
     * @param int $agent
     * @param int $country
     * @param string $result The XML string of the scrape.
     * @param string $messages The scrape message to save.
     * @return FailedScrapes
     */
    public function saveFailedScrapes($agent, $country, $result, $messages = '')
    {
        $failedScrape = $this->entityFactory->createFailedScrapes(
            array("result"  => $result, "message" => $messages,)
        );
        return $this->scrapeRepo->saveFailedScrapes(
            $agent, 
            $country, 
            $failedScrape
        );
    }
    
    protected function buildPropertyFromDOM(\DOMDocument $data) {
        $scrapeProperty = array();

        $country = rawurldecode($data->getElementsByTagName('country')->item(0)->nodeValue);
        $agent = rawurldecode($data->getElementsByTagName('agent')->item(0)->nodeValue);

        $marketer = $data->getElementsByTagName('marketer');
        if ($marketer) {
            $scrapeProperty['marketer'] = $marketer->item(0)->nodeValue;
        }

        $address = $data->getElementsByTagName('address');
        if ($address) {
            $scrapeProperty['address'] = $address->item(0)->nodeValue;
        }

        $room = $data->getElementsByTagName('rooms');
        if (!is_null($room->item(0))) {
            $scrapeProperty['rooms'] = $room->item(0)->nodeValue;
        }

        $postcode = $data->getElementsByTagName('areacode')->item(0)->nodeValue;

        $phone = $data->getElementsByTagName('phone');
        if ($phone->length) {
            $scrapeProperty['phone'] = $phone->item(0)->nodeValue;
        }

        $scrapeProperty['price'] = doubleval($data->getElementsByTagName('price')->item(0)->nodeValue);

        $scrapeProperty["offer_type"] = $data->getElementsByTagName("offertype")->item(0)->nodeValue;
        $type = $data->getElementsByTagName("type")->item(0)->nodeValue;

        $scrapeProperty['url'] = rawurldecode($data->getElementsByTagName('url')->item(0)->nodeValue);

        $scrapeProperty['hash'] = $this->generatePropertyHash(
            $country,
            $agent,
            $scrapeProperty['address'],
            $scrapeProperty['marketer'],
            $postcode,
            $scrapeProperty["offer_type"],
            $type,
            $scrapeProperty["url"]
        );

        $property = $this->entityFactory->createProperty($scrapeProperty);

        return $property;
    }

    /**
     * @return string The hash string generated.
     */
    public function generatePropertyHash()
    {
        $parmeters = func_get_args();
        $hashString = '';
        foreach ($parmeters as $parmeter) {
            $hashString .= strtolower($parmeter);
        }

        return hash("md5", $hashString);
    }

    
}

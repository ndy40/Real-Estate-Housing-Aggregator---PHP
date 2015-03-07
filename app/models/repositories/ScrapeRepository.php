<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\repositories;

use models\interfaces\AgentRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use models\entities\FailedScrapes;
use models\crawler\abstracts\JobQueue;
use Illuminate\Support\Facades\Log;
use models\entities\Property;
use models\interfaces\PropertyRespositoryInterface;
use models\interfaces\ScrapeRepositoryInterface;
use Illuminate\Support\Facades\Queue;

use models\entities\PropertyType;

use postcode\Postcode;
/**
 * Description of ScrapeRepository
 *
 * @author ndy40
 */
class ScrapeRepository implements ScrapeRepositoryInterface
{
    protected $propertyRepo;

    protected $agentRepo;

    /**
     * Constructor to create ScrapeRepository Insteance.
     *
     * @param \models\interfaces\PropertyRespositoryInterface $propRepo
     * @param \models\interfaces\AgentRepositoryInterface $agentRepo
     */
    public function __construct(
        PropertyRespositoryInterface $propRepo,
        AgentRepositoryInterface $agentRepo
    ){
        $this->propertyRepo = $propRepo;
        $this->agentRepo = $agentRepo;
    }

    public function delete($id)
    {
    }

    public function fetch($id)
    {
    }

    public function update($entity)
    {
    }

    /**
     * This saves failed scrape jobs into the
     *
     * @param string $country - Country code.
     * @param string $agent - Agency name.
     * @param FailedScrapes $failedScrape An insteance of the FailedScrape class.
     */
    public function saveFailedScrapes(
        $agent,
        $country,
        FailedScrapes $failedScrape
    ){
        $agency = $this->agentRepo->fetchAgentByNameAndCountry(
            $agent,
            $country
        );

        $agency->failedScrapes()->save($failedScrape);

        return $failedScrape;
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

        $postCodes = $this->propertyRepo->fetchPostCode($postCode);

        if (!$postCodes->isEmpty() && $postCodes->count() == 1) {
            $scrapedProperty->postCode()->associate($postCodes->first());
        } elseif (!$postCodes->isEmpty() && $postCodes->count() > 0) {
            $regex = $this->getPostCodeAreaRegex($postCode);
            $area = $this->extractAreaName($scrapedProperty->address, $regex);

            //Since we couldn't find the area name No choice but to use county
            if (empty($area)) {
                //then we probably have county listed there. Insert new post code against county.
                $regex = $this->getCountyRegex($agency->country->id);
                $area = $this->extractAreaName($scrapedProperty->address, $regex);

                $existingPostCode = $this->propertyRepo->fetchPostCodeByName($postCode, $area);

                if (empty($existingPostCode)) {
                    $isSaved = $this->propertyRepo->createPostCode(
                        $agency->country->id,
                        $postCode,
                        $area
                    );
                } elseif (empty($area)) {
                    $format = sprintf(
                        "Cannot find county in address - %s in scrape address",
                        $scrapedProperty->address
                    );
                    Log::error($format);
                    return;
                }
            }
            $postCode = $this->propertyRepo->fetchPostCodeByName($postCode, $area);
            $scrapedProperty->postCode()->associate($postCode);
        } else {
            $format = sprintf("Cannot find post code in database %s", $postCode);
            Log::error($format);
            return;
        }

        switch ($status) {
            case JobQueue::ITEM_NOT_AVAILABLE:
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
                    $scrapedProperty = $this->propertyRepo->save($scrapedProperty);
                    $action = "create";
                } else {
                    //perform proper test on data insert/update of property.
                    $count = $this->propertyRepo->updateChangedFields($scrapedProperty, $property);
                    $scrapedProperty = $this->propertyRepo->save($property);
                    $action = "update";
                }

                if ($scrapedProperty === false) {
                    Log::warning("Failed to save property " . $scrapedProperty);
                } else {
                    $images = $this->createImagesArray($data);
                    $jobData = array (
                        "property_id" => $scrapedProperty->id,
                        "images"      => $images,
                        "action"      => $action
                    );
                    Queue::push("ImageProcessingQueue", $jobData, "scrape_images");
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

        $phone = $data->getElementsByTagName('phone');
        if ($phone->length) {
            $scrapeProperty['phone'] = $phone->item(0)->nodeValue;
        }

        $scrapeProperty['price'] = doubleval($data->getElementsByTagName('price')->item(0)->nodeValue);

        $scrapeProperty["offer_type"] = $data->getElementsByTagName("offertype")->item(0)->nodeValue;
        $type = $data->getElementsByTagName("type")->item(0)->nodeValue;

        $scrapeProperty['url'] = rawurldecode($data->getElementsByTagName('url')->item(0)->nodeValue);

        $scrapeProperty['description'] = $data->getElementsByTagName('description')->item(0)->nodeValue;

        $scrapeProperty['hash'] = $this->propertyRepo->generatePropertyHash(
            $country,
            $agent,
            $scrapeProperty['address'],
            $scrapeProperty['marketer'],
            $postcode,
            $scrapeProperty["offer_type"],
            $type,
            $scrapeProperty["url"]
        );
        $property = new Property();
        $property->assignAttributes($scrapeProperty);

        return $property;
    }

    public function save($entity)
    {
        return $entity->save();
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

    public function getPostCodeAreaRegex($postCode)
    {
        $key = "county_area_regex_{$postCode}";
        if (Cache::has($key)) {
            $regex =  Cache::get($key);
        } else {
            $postCodes = $this->propertyRepo->fetchPostCode($postCode);
            $areas = array();
            foreach ($postCodes as $postCode) {
                //$areas[] = str_replace(" ", "\s", strtolower($postCode->area));
                $area_str = str_replace(" ", "\s", strtolower($postCode->area));
                $area_str = str_replace("/", "\/", $area_str);
                $areas[] = $area_str;
            }
            $regex = implode("|", $areas);
            Cache::put($key, $regex, 10);
        }

        return $regex;
    }

    public function extractAreaName($address = '', $regex = '')
    {
        $matches = null;
        if (preg_match("/{$regex}/i", $address, $matches)) {
            return trim($matches[0]);
        }

        return false;
    }

    public function getCountyRegex($country)
    {
        $key = "county_regex_" . $country;
        if (Cache::has($key)) {
            $result = Cache::get($key);
        } else {
            $country = $this->agentRepo->fetchCountry((int)$country);
            $countyList = array();
            if ($country) {
                $counties = $country->counties;
                foreach ($counties as $county) {
                    $countyList[] = str_replace(" ", "\s", strtolower($county->name));
                }
                $result = implode("|", $countyList);
                Cache::put($key, $result, 20);
            }
        }

        return $result;
    }
    
    public function createImagesArray(\DOMDocument $dom)
    { 
        $imgs = $dom->getElementsByTagName("src");
        $images = array();
        foreach($imgs as $image) {
            $images[] = $image->nodeValue;
        }
        
        return $images;
    }
}

<?php
namespace models\repositories;

use models\repositories\ScrapeRepository;
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
 * @author kaimin
 */
class FeedRepository extends ScrapeRepository
{
    public function saveProperty(\DOMDocument $data)
    {
        //change rules for Property because url for feed is empty.
        $rules = Property::$rules;
        $origin_rules = $rules;
        $rules['url'] = '';
        Property::$rules = $rules;

        parent::saveProperty($data);

        Property::$rules = $origin_rules;
    }
//        $scrapedProperty = $this->buildPropertyClassPart($data);
//
//        $status = $data->getElementsByTagName('status')->item(0)->nodeValue;
//        $agent = $data->getElementsByTagName('agent')->item(0)->nodeValue;
//        $country = $data->getElementsByTagName('country')->item(0)->nodeValue;
//
//        $type = $data->getElementsByTagName('type')->item(0)->nodeValue;
//        $type = $this->propertyRepo->fetchPropertyType($type);
//        $scrapedProperty->type()->associate($type);
//
//        $postCode = $data->getElementsByTagName('areacode')->item(0)->nodeValue;
//
//        $agency = $this->agentRepo->fetchAgentByNameAndCountry($agent, $country);
//        $scrapedProperty->agency()->associate($agency);
//
//        $postCodes = $this->propertyRepo->fetchPostCode($postCode);
//
//        if (!$postCodes->isEmpty() && $postCodes->count() == 1) {
//            $scrapedProperty->postCode()->associate($postCodes->first());
//        } elseif (!$postCodes->isEmpty() && $postCodes->count() > 0) {
//            $regex = $this->getPostCodeAreaRegex($postCode);
//            $area = $this->extractAreaName($scrapedProperty->address, $regex);
//
//            //Since we couldn't find the area name No choice but to use county
//            if (empty($area)) {
//                //then we probably have county listed there. Insert new post code against county.
//                $regex = $this->getCountyRegex($agency->country->id);
//                $area = $this->extractAreaName($scrapedProperty->address, $regex);
//
//                $existingPostCode = $this->propertyRepo->fetchPostCodeByName($postCode, $area);
//
//                if (empty($existingPostCode)) {
//                    $isSaved = $this->propertyRepo->createPostCode(
//                        $agency->country->id,
//                        $postCode,
//                        $area
//                    );
//                } elseif (empty($area)) {
//                    $format = sprintf(
//                        "Cannot find county in address - %s in scrape address",
//                        $scrapedProperty->address
//                    );
//                    Log::error($format);
//                    return;
//                }
//            }
//            $postCode = $this->propertyRepo->fetchPostCodeByName($postCode, $area);
//            $scrapedProperty->postCode()->associate($postCode);
//        } else {
//            $format = sprintf("Cannot find post code in database %s", $postCode);
//            Log::error($format);
//            return;
//        }
//
//        switch ($status) {
//            case JobQueue::ITEM_NOT_AVAILABLE:
//                $property = $this->propertyRepo
//                    ->fetchPropertyByHash($scrapedProperty->hash);
//
//                if (!is_null($property)) {
//                    $property->available = false;
//                    $property->save();
//                }
//                break;
//            case JobQueue::ITEM_AVAILABLE:
//                $scrapedProperty->available = 1;
//                $property = $this->propertyRepo
//                    ->fetchPropertyByHash($scrapedProperty->hash);
//                //check if agency has been configured for auto-approval. If true then
//                //set available to 1.
//
//                if ($agency->auto_publish) {
//                    $scrapedProperty->published = 1;
//                }
//
//                //change rules for Property because url for feed is empty.
//                $rules = Property::$rules;
//                $origin_rules = $rules;
//                $rules['url'] = '';
//                Property::$rules = $rules;
//
//                if ($property === null) {
//                    $scrapedProperty = $this->propertyRepo->save($scrapedProperty);
//                    $action = "create";
//                } else {
//                    //perform proper test on data insert/update of property.
//                    $count = $this->propertyRepo->updateChangedFields($scrapedProperty, $property);
//                    $scrapedProperty = $this->propertyRepo->save($property);
//                    $action = "update";
//                }
//
//                Property::$rules = $origin_rules;
//
//                if ($scrapedProperty === false) {
//                    Log::warning("Failed to save property " . $scrapedProperty);
//                } else {
//                    $images = $this->createImagesArray($data);
//                    $jobData = array (
//                        "property_id" => $scrapedProperty->id,
//                        "images"      => $images,
//                        "action"      => $action
//                    );
//                    Queue::push("ImageProcessingQueue", $jobData, "scrape_images");
//                }
//
//                break;
//        }
//
//        return $scrapedProperty;
//    }
}
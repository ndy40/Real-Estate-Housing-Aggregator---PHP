<?php

namespace controllers\search;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use BaseController;

/**
 * Description of SearchController
 *
 * @author ndy40
 */
class SearchController extends BaseController {

    protected $propertyLogic;

    public function __construct() {
        $this->propertyLogic = App::make("PropertyLogic");
    }

    public function getPostCodes($location) {
        $data = null;
        $cacheIndex = "postcodes_" . $location;

        if (Cache::has($cacheIndex)) {
            $data = Cache::get("location_" . $location);
        } else {
            $data = $this->propertyLogic
                            ->searchLocationByCountyAndPostCode($location)->toArray();
            Cache::put($cacheIndex, $data, 10);
        }

        return Response::json(array("data" => $data));
    }

    public function getLocation($name) {
        $data = null;
        $cacheIndex = "location_" . $name;
        if (Cache::has($cacheIndex)) {
            $data = Cache::get($cacheIndex);
        } else {
            $data = $this->propertyLogic->searchLocation($name)->toArray();
            Cache::put($cacheIndex, $data, 10);
        }

        return Response::json(array("data" => $data));
    }

    public function getPropertyTypes() {
        $data = null;
        if (Cache::has("property_types")) {
            $data = Cache::get("property_types");
        } else {
            $properties = $this->propertyLogic->findPropertyTypes("name");
            if (!$properties->isEmpty()) {
                $data = $properties->toArray();
            }
        }

        return Response::json(array("data" => $data));
    }

    public function getPropertyDetailTypes($property_type) {
        $properties = $this->propertyLogic->findPropertyDetailTypes($property_type);
        if (!$properties->isEmpty()) {
            $data = $properties->toArray();
        }
        return Response::json(array("data" => $data));
    }

    public function getSearchProperties($filter = "", $startIndex = 1, $size = 25) {
        if (!isset($filter) || empty($filter)) {
            return Response::json("false", 400);
        }

        $queryString = Input::query();

        $cacheKey = "search_prop_" . $filter . $startIndex . $size . implode("_", array_keys($queryString)) . preg_replace("/\s/", '%', implode("_", $queryString));
        $data = null;
        if (Cache::has($cacheKey)) {
            $response = Cache::get($cacheKey);
        } else {
            $column = Input::has("column") ? Input::get("column") : "updated_at";
            $direction = Input::has("direction") ? Input::get("direction") : "asc";

            $data = $this->propertyLogic->searchProperty(
                            $filter, true, $column, $direction, $startIndex, $size, $queryString
                    )->toArray();

            $count = $this->propertyLogic->searchPropertyCount($filter, true, $queryString);

            if (!isset($count)) {
                $startIndex = null;
                $size = null;
            }
            $response = array("count" => $count, "page" => $startIndex, "size" => $size, "data" => $data);

            if (isset($data) && !empty($data)) {
                //add to cache
                Cache::put($cacheKey, $response, 1);
            }
        }

        return Response::json($response, 200);
    }

    public function getAvgRoomPrices($countyId) {
        $data = $this->propertyLogic->getAvgRoomPricesByCounty($countyId);

        if (isset($data)) {
            $response = $data;
        }

        return Response::json(array("data" => $response));
    }

    public function getUserProperties() {
        $authLogic = App::make("AuthLogic");
        $user = $authLogic->getCurrentUser();
        $data = $user->savedProperties()->with("property")->get()->toArray();

        return Response::json(array("data" => $data));
    }

    public function postSaveUserProperty() {
        $data = Input::get("data");
        $propertyId = $data["property_id"];
        $calculations = json_encode($data["calculations"]);

        $authLogic = App::make("AuthLogic");
        $userId = $authLogic->getCurrentUser()->id;

        $saved = $this->propertyLogic->saveUserProperty($userId, $propertyId, $calculations);

        return Response::json($saved);
    }

    public function getRemoveSavedProperty($userId, $propertyId) {
        
    }

    public function getPropertyHistory($id) {
        $property = $this->propertyLogic->find($id);

        if ($property) {
            $data = $property->history->toArray();
        } else {
            $data = array();
        }

        return Response::json(array("data" => $data));
    }

    public function getProperty($id) {
        $property = $this->propertyLogic->find($id);

        if ($property) {
            $result = $property->toArray();
            $type = $this->propertyLogic->findPropertySearchTypes($property->type_id);
            $result['type'] = $type->name;
            return Response::json($result, 200);
        }
        return Response::json(array("flash" => "Property not found."), 401);
    }

    public function getPropertiesByIds($property_ids) {
        $ids = explode(",", $property_ids);
        $responseCode = 400;
        if (is_array($ids)) {
            $properties = $this->propertyLogic->getPropertyByIds($ids);
            if ($properties) {
                $data = $properties->toArray();
                $responseCode = 200;
            }
        } else {
            $data = false;
        }

        return Response::json($data, $responseCode);
    }

    public function getAverageRentalYield($postCodeId, $numOfRooms, $typeId, $offerType = 'Sale') {

        $avg_price = $this->propertyLogic->getAveragePrice(
                $postCodeId, $numOfRooms, $typeId, $offerType
        );

        return Response::json(array("data" => $avg_price));
    }

    public function getComparableProperties($propertyId) {
        $comparables = $this->propertyLogic->getComparableProperties($propertyId);
        return Response::json($comparables);
    }

    public function getSearchAutoCompleteOptions($search, $recordCount = 10) {
        $suggestions = $this->propertyLogic->getAutoCompleteSuggestion($search, $recordCount);
        $count = count($suggestions);
        if (count($suggestions) > 0) {
            $suggestions = $suggestions->toArray();
        }
        return Response::json(array("count" => $count, "data" => $suggestions));
    }

}

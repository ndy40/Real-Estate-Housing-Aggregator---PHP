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
class SearchController extends BaseController
{
    protected $propertyLogic;

    public function __construct()
    {
        $this->propertyLogic = App::make("PropertyLogic");
    }

    public function getPostCodes($location)
    {
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

    public function getLocation($name)
    {
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

    public function getPropertyTypes()
    {
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

    public function getSearchProperties($filter, $startIndex = 1, $size = 25)
    {
        $cacheKey = "search_prop_" . $filter . $startIndex . $size;
        $data = null;
        if (Cache::has($cacheKey)) {
            $data = Cache::get($cacheKey);
        } else {
//            $filter = Input::get("search");
            $column = Input::has("column") ? Input::get("column") : "updated_at";
            $direction = Input::has("direction") ? Input::get("direction") : "asc";

            $data = $this->propertyLogic->searchProperty(
                $filter,
                false,
                $column,
                $direction,
                $startIndex,
                $size
            )->toArray();
            $count = $this->propertyLogic->searchPropertyCount($filter, false);
            if (!isset($count)) {
                $startIndex = null;
                $size = null;
            }
        }

        return Response::json(array("count" => $count, "page" => $startIndex, "size" => $size, "data" => $data));
    }

    public function getAvgRoomPrices($countyId)
    {
        $data = $this->propertyLogic->getAvgRoomPricesByCounty($countyId);

        if (isset($data)) {
            $response = $data;
        }

        return Response::json(array("data" => $response));
    }

    public function getUserProperties()
    {
        $authLogic = App::make("AuthLogic");
        $user = $authLogic->getCurrentUser();
        $data = $user->savedProperties()->with("property")->get()->toArray();

        return Response::json(array("data" => $data));
    }

    public function postSaveUserProperty()
    {
        $data = Input::get("data");
        $propertyId = $data["property_id"];
        $calculations = json_encode($data["calculations"]);

        $authLogic = App::make("AuthLogic");
        $userId = $authLogic->getCurrentUser()->id;

        $saved = $this->propertyLogic->saveUserProperty($userId, $propertyId, $calculations);

        return Response::json($saved);
    }

    public function getRemoveSavedProperty($userId, $propertyId)
    {
    }

    public function getPropertyHistory($id)
    {
        $property = $this->propertyLogic->find($id);

        if ($property) {
            $data = $property->history->toArray();
        } else {
            $data = array();
        }

        return Response::json(array("data" => $data));
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\repositories;

use Illuminate\Support\Facades\DB;
use models\entities\PropertyChangeLog;
use models\interfaces\PropertyRespositoryInterface;
use models\entities\PostCode;
use models\entities\Property;
use models\entities\PropertyType;
use models\entities\PropertySearchType;
use Illuminate\Support\Facades\App;
use models\entities\Country;
use models\entities\County;
use \models\entities\Image;

/**
 * Description of PropertyRepository
 *
 * @author ndy40
 */
class PropertyRepository implements PropertyRespositoryInterface {

    public function delete($id) {
        
    }

    public function fetch($id) {
        return Property::with("images")->find($id);
    }

    public function update($entity) {
        throw new \Exception("Not implemented yet");
    }

    public function save($entity) {
        if ($entity->save()) {
            return $entity;
        }

        return false;
    }

    public function fetchPropertyByHash($hash) {
        return Property::where('hash', '=', $hash)->first();
    }

    public function fetchPostCode($postcode) {
        $postCode = null;
        if (is_numeric($postcode)) {
            $postCode = PostCode::find($postcode);
        } else {
            $postCode = PostCode::where('code', '=', strtoupper($postcode))
                    ->get();
        }
        return $postCode;
    }

    public function fetchPostCodeByName($code, $areaName) {
        return PostCode::where("area", "=", ucwords(strtolower(trim($areaName))))
                        ->where("code", "=", strtoupper($code))
                        ->first();
    }

    //TODO: Investigate.
    public function insertProperty($agencyId, $data = array()) {
        $agencyRepo = App::make('AgentRepository');
        $agency = $agencyRepo->fetch($agencyId);
        if (empty($data)) {
            throw new Exception("No initial data provided");
        }

        $property = new Property($data);
    }

    public function fetchPropertyType($type) {
        if (is_numeric($type)) {
            $type = PropertyType::find($type);
        } else {
            $type = PropertyType::where('name', 'like', "%{$type}%")
                    ->firstOrFail();
        }
        return $type;
    }

    public function fetchAndInsertPropertyType($type) {
        $types = array();
        if (is_string($type)) {
            $types = PropertyType::where('name', 'like', "%{$type}%")->get();
        } else {
            $types = PropertyType::find($type);
        }

        if ($types->isEmpty()) {
            $property_type = new PropertyType;
            $property_type->name = $type;
            $property_type->save();
            return $property_type;
        }

        return $types[0];
    }

    /**
     * Method to update difference in values between scraped and db object.
     *
     * @param \models\entities\Property $scrapedProperty Property being scraped
     * @param \models\entities\Property $dbProperty Property from DB that should be
     * compared against.
     * @return boolean True if something has been updated else false returned.
     */
    public function updateChangedFields($scrapedProperty, &$dbProperty) {
        $changeArray = array();
        $fields = array('marketer', 'price', 'address', 'url', "phone", "rooms", "offer_type");
        $changeCount = 0;
        foreach ($fields as $key) {
            if ($scrapedProperty->{$key} != $dbProperty->{$key}) {
                $changeArray[$key] = $scrapedProperty->{$key};
                $changeCount++;
            }
        }
        $dbProperty->postCode()->associate($scrapedProperty->postCode);
        $dbProperty->assignAttributes($changeArray);

        return $changeCount;
    }

    public function fetchCountries() {
        return Country::all();
    }

    public function fetchAllCounty() {
        return County::orderBy("name", "asc")->get();
    }

    /**
     * Fetch county by name or Id.
     *
     * @param string|int $county
     * @return Collection|County
     */
    public function fetchCounty($county) {
        if (is_numeric($county)) {
            $result = County::with("postCodes")->find($county);
        } else {
            $result = County::with("postCodes")->where("name", "=", ucwords($county))->firstOrFail();
        }
        return $result;
    }

    public function deleteCounty($id) {
        $county = County::fetch($id);
        return $county->delete();
    }

    public function deletePostCode($id) {
        $postCode = PostCode::find($id);
        return $postCode->delete();
    }

    public function savePropertyChangelog(PropertyChangeLog $changeLog) {
        return $changeLog->save();
    }

    /**
     * Get all properties and filter accordingly.
     *
     * @param mixed[] $filter
     * @param type $startIndex
     * @param type $size
     * @return type
     */
    public function fetchAllProperty($filter = array(), $startIndex = 1, $size = 25) {
        $skip = $size * ($startIndex - 1);
        if (empty($filter)) {
            return Property::with("agency", "postCode", "type")
                            ->skip($skip)
                            ->take($size)
                            ->get();
        }

        if (array_key_exists("post_code_id", $filter)) {
            return Property::with("agency", "postCode", "type")
                            ->where("post_code_id", "=", $filter["post_code_id"])
                            ->skip($skip)
                            ->take($size)
                            ->get();
        } elseif (array_key_exists("county", $filter)) {
            return Property::with("agency", "postCode", "type")->whereHas("postCode", function ($query) use ($filter) {
                                        $query->where("county_id", "=", $filter["county"]);
                                    })
                            ->skip($skip)
                            ->take($size)
                            ->get();
        }
    }

    /**
     * @param mixed[] $filter parameters to filter by
     * @return int The count.
     */
    public function countAllProperty($filter) {
        if (empty($filter)) {
            return Property::with("agency", "postCode", "type")->count();
        }

        if (array_key_exists("post_code_id", $filter)) {
            return Property::with("agency", "postCode", "type")
                            ->where("post_code_id", "=", $filter["post_code_id"])->count();
        } elseif (array_key_exists("county", $filter)) {
            return Property::with("agency", "postCode", "type")->whereHas("postCode", function ($query) use ($filter) {
                                $query->where("county_id", "=", $filter["county"]);
                            })->count();
        }
    }

    public function searchLocationByCountyAndPostCode($location) {
        return PostCode::with("county")
                        ->where("code", "like", "%{$location}%", "or")
                        ->orWhere("area", "like", "%{$location}%")
                        ->orWhereHas("county", function ($query) use ($location) {
                                    $query->where("name", "like", "%{$location}%");
                                })
                        ->orderBy("code")->get();
    }

    public function searchLocation($name) {
        return County::where("name", "like", "%{$name}%")->orderBy("name")->get();
    }

    public function findPropertyDetailTypes($search_type_id) {
        return PropertyType::where("search_type_id", "=", $search_type_id)->get();
    }

    public function findPropertySearchTypes($type_id) {
        $type = $this->fetchPropertyType($type_id);
        return PropertySearchType::find($type->search_type_id);
    }

    public function findPropertyTypes($columnName, $direction) {
        return PropertySearchType::orderBy($columnName, $direction)->get();
    }

    public function searchProperty($filter, $isPublished = false, $orderColumn = "updated_at", $direction = "asc", $startIndex = 1, $size = 25, $queryString = '') {
        $startIndex = ($startIndex - 1);
        $index = $size * $startIndex;

        $query = Property::with(array("agency", "postCode", "type", "images"))
                ->where("published", "=", $isPublished, "and");
        if (!empty($filter)) {
            $query->where(function ($query) use ($filter) {
                        // exploading WC1X High Holborn into WC1X & High Holborn texts.
                        $filterArr = explode(' ', $filter, 2);
                        foreach ($filterArr as $filterVal) {
                            $query->orWhere("address", 'like', "%$filterVal%");
                        }

                        foreach ($filterArr as $filterVal) {
                            $query->orWhereHas("type", function ($query) use ($filterVal) {
                                        $query->where("name", "like", "%$filterVal%");
                                    });
                        }

                        foreach ($filterArr as $filterVal) {
                            $query->orWhereHas("postCode", function ($query) use ($filterVal) {
                                        $query->join("county", "county.id", "=", "post_codes.id")
                                                ->whereNested(function ($q) use ($filterVal) {
                                                            $q->where("area", "like", "%$filterVal%")
                                                            ->orWhere("code", "=", $filterVal)
                                                            ->orWhere("county.name", "like", "%$filterVal%");
                                                        });
                                    });
                        }
                    });
        }

        if (is_array($queryString)) {
            foreach ($queryString as $key => $value) {
                $query->where($value[0], $value[1], $value[2]);
            }
        }

        if ($direction === "") {
            $output = $query->skip($index)
                    ->take($size)
                    ->get();
        } else {
            $output = $query->orderBy($orderColumn, $direction)
                    ->skip($index)
                    ->take($size)
                    ->get();
        }

        return $output;
    }

    public function searchPropertyCount($filter, $isPublished = true, $queryString = '') {
        $query = Property::with(array("agency", "postCode", "type", "images"))
                ->where("published", "=", $isPublished, "and");
        if (!empty($filter)) {
            $query->where(function ($query) use ($filter) {
                        $filterArr = explode(' ', $filter, 2);
                        foreach ($filterArr as $filterVal) {
                            $query->orWhere("address", 'like', "%$filterVal%");
                        }

                        foreach ($filterArr as $filterVal) {
                            $query->orWhereHas("type", function ($query) use ($filterVal) {
                                        $query->where("name", "like", "%$filterVal%");
                                    });
                        }

                        foreach ($filterArr as $filterVal) {
                            $query->orWhereHas("postCode", function ($query) use ($filterVal) {
                                        $query->join("county", "county.id", "=", "post_codes.id")
                                                ->whereNested(function ($q) use ($filterVal) {
                                                            $q->where("area", "like", "%$filterVal%")
                                                            ->orWhere("code", "=", $filterVal)
                                                            ->orWhere("county.name", "like", "%$filterVal%");
                                                        });
                                    });
                        }
                    });
        }

        if (is_array($queryString)) {
            foreach ($queryString as $key => $value) {
                $query->where($value[0], $value[1], $value[2]);
            }
        }

        return $query->count();
    }

    /**
     * Method for calulating average room prices for a location/county.
     *
     * @param mixed $county_id
     * @return array
     */
    public function getAvgRoomPricesByCounty($county_id) {
        return DB::table("properties")
                        ->join("post_codes", "properties.post_code_id", "=", "post_codes.id")
                        ->select(
                                DB::raw(
                                        "post_codes.county_id as county_id,
                    post_codes.id as post_code_id, rooms, cast(avg(price) as decimal(15,2)) as avg"
                                )
                        )
                        ->whereNotNull("rooms")
                        ->where("post_codes.county_id", "=", $county_id)
                        ->groupBy("rooms")
                        ->get();
    }

    /**
     * Create a new post code.
     *
     * @param string|int $county Id or name of county
     * @param string $postCode Post code
     * @param string $area Name of the area
     * @return PostCode|false Returns PostCode instance if successful or false if failed
     */
    public function createPostCode($county, $postCode, $area) {
        $county = $this->fetchCounty($county);
        $postCode = new PostCode(array("area" => ucwords($area), "code" => strtoupper($postCode)));
        $postCode->county()->associate($county);

        return $postCode->save();
    }

    public function deleteSavedProperty($userid, $propertyid) {
        $auth = App::make("AuthLogic");
    }

    /**
     * This should be deprecated soon.
     *
     * @return string The hash string generated.
     */
    public function generatePropertyHash() {
        $parmeters = func_get_args();
        $hashString = '';
        foreach ($parmeters as $parmeter) {
            $hashString .= strtolower($parmeter);
        }

        return hash("md5", $hashString);
    }

    public function savePropertyImages($id, $images = null) {
        if (is_null($images) || empty($images)) {
            return false;
        }
        try {
            $property = Property::findOrFail($id);
            foreach ($images as $image) {
                $property->images()->save($image);
            }

            return true;
        } catch (Exception $ex) {
            return false;
        }

        return false;
    }

    public function deleteImage($id) {
        return Image::find($id)->delete();
    }

    public function deleteOldProperty($date) {
        $oldproperties = Property::where('updated_at', '<', $date);
        $oldproperties->delete();
    }

    /**
     * This computes the average price on a property. OfferType parameter defaults to Sale.
     * Rent can be sent passed to compute average rental price.
     *
     * @param int $post_code_id - Post Code Id
     * @param int $rooms - Number of rooms
     * @param int $type_id - The Type ID for the property
     * @param string $offerType - The Offer type to compute asking price on e.g sale|rent. Optional
     */
    public function getAveragePrice($post_code_id, $rooms, $type_id, $offerType = 'sale') {
        $sql = "SELECT convert(coalesce(avg(price), 0), DECIMAL(15,2)) as avg_price"
                . " FROM properties WHERE type_id = ? and rooms = ? and post_code_id = ?"
                . " and offer_type = ?";
        $params = array($type_id, $rooms, $post_code_id, $offerType);
        $average = DB::selectOne($sql, $params);

        if (!empty($average)) {
            return $average->avg_price;
        }

        return null;
    }

    /**
     * Get Properties by passing a set of Ids.
     * @param array $ids
     */
    public function getPropertyByIds(array $ids) {
        if (!is_array($ids)) {
            throw new \InvalidArgumentException("Array parameter expected.");
        }

        return Property::whereIn("id", $ids)->get();
    }

    public function getPropertiesByType($type, $recordCount = 3) {
        if ($type == 'HighestYield')
            $sql = "SELECT p.id, i.`image` image, p.rooms, p.address, p.yield, p.phone, p.`price`, (p.yield * p.`price`)/(12*100) rent
                    FROM properties p
                        JOIN images i ON p.`id` = i.`property_id` 
                    WHERE p.offer_type = ? 
                    GROUP BY p.id
                    ORDER BY p.yield DESC, p.updated_at DESC 	
                    LIMIT ?";
        elseif ($type == 'HighReduction') {
            $sql = "SELECT * FROM (
                            SELECT p.id, i.`image` image, p.rooms, p.address, p.yield, ((pcl.`old_price`- pcl.`new_price`)/pcl.`old_price`)*100 AS redpercent,
                            pcl.`old_price`, pcl.`new_price`, (p.yield * p.`price`)/(12*100) rent, p.`price`, p.`updated_at`,
                            (pcl.`old_price`- pcl.`new_price`) AS priceDiff
                            FROM properties p
                            LEFT JOIN property_change_logs pcl ON p.`id` = pcl.`property_id` 
                            JOIN images i ON p.`id` = i.`property_id` 
                            WHERE p.offer_type = ? AND 
                            pcl.`new_price` IS NOT NULL AND (p.yield > 0 AND p.yield IS NOT NULL)
                            ORDER BY pcl.property_id, p.`updated_at` DESC
                    ) AS t 
                    WHERE t.old_price > t.new_price AND redpercent > 0.1
                    GROUP BY t.id ORDER BY priceDiff LIMIT ?";
        }

        $params = array('sale', $recordCount);
        $propertyList = DB::select($sql, $params);
        return $propertyList;
    }

    public function getAutoCompleteSuggestion($searchText, $recordCount = 10) {
        if (is_numeric($searchText)) {
            return PostCode::find($searchText);
        } else {
            return PostCode::whereRaw("code like '%" . strtoupper(trim($searchText)) . "%' or area like '%" . trim($searchText) . "%'")
                            ->take($recordCount)->get();
        }
    }

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\repositories;

use models\entities\PropertyChangeLog;
use models\interfaces\RepositoryInterface;
use models\entities\PostCode;
use models\entities\Property;
use models\entities\PropertyType;
use Illuminate\Support\Facades\App;
use models\entities\Country;
use models\entities\County;
/**
 * Description of PropertyRepository
 *
 * @author ndy40
 */
class PropertyRepository implements RepositoryInterface
{
    public function delete($id) 
    {
        
    }

    public function fetch($id) 
    {
        return Property::find($id);
    }

    public function update($entity) 
    {
        
    }
    
    public function save($entity) {
        return $entity->save();
    }

    
    public function fetchPropertyByHash($hash) {
        return Property::where('hash', '=', $hash)->first();
    }

    public function fetchPostCode ($postcode)
    {
        $postCode = null;
        if (is_numeric($postCode)) {
            $postCode = PostCode::find($postcode);
        } else {
            $postCode = PostCode::where('code', '=', strtoupper($postcode))
                ->firstOrFail();
        }
        return $postCode;
    }
    
    public function generatePropertyHash(
        $country, 
        $agent, 
        $address, 
        $marketer, 
        $postcode
    ){
        $hashString = strtolower($country) . strtolower($agent) 
            . strtolower($address) . strtolower($marketer) 
            . strtolower($postcode);
        
        return ($hashString);
    }
    
    public function insertProperty($agencyId, $data = array()) {
        $agencyRepo = App::make('AgentRepository');
        $agency = $agencyRepo->fetch($agencyId);
        if (empty($data)) {
            throw new Exception("No initial data provided");
        }
        $property = new Property($data);
    }
    
    public function fetchPropertyType($type) {
        if (is_string($type)) {
            $type = PropertyType::where('name', 'like', "%{$type}%")
                ->firstOrFail();
        } else {
            $type = PropertyType::find($type);
        }
        return $type;
    }
    
    /**
     * Method to update difference in values between scraped and db object.
     * 
     * @param \models\entities\Property $scrapedProperty Property being scraped
     * @param \models\entities\Property $dbProperty Property from DB that should be
     * compared against.
     * @return boolean True if something has been updated else false returned.
     */
    public function updateChangedFields($scrapedProperty, $dbProperty)
    {
        $changeArray = array();
        $fields = array('marketer', 'price', 'address', 'url', 'availabile');
        foreach($fields as $key) {
            if ($scrapedProperty->{$key} !== $dbProperty->{$key}) {
                $changeArray[$key] = $scrapedProperty->{$key};
            }
        }
        
        $dbProperty->postCode()->associate($scrapedProperty->postCode);
        $dbProperty->assignAttributes($changeArray);
        return $dbProperty;
    }
    
    public function fetchCountries()
    {
        return Country::all();
    }

    public function fetchAllCounty(){
        return County::orderBy("name", "asc")->get();
    }
    
    public function fetchCounty($id)
    {
        return County::with("postCodes")->find($id);
    }
    
    public function deleteCounty($id)
    {
        $county = County::fetch($id);
        return $county->delete();
    }
    
    public function deletePostCode($id) {
        $postCode = PostCode::find($id);
        return $postCode->delete();
    }

    public function savePropertyChangelog(PropertyChangeLog $changeLog)
    {
        return $changeLog->save();
    }

    public function fetchAllProperty($filter = array(), $startIndex = 1, $size = 25)
    {
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
        } else if (array_key_exists("county", $filter)) {
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
    public function countAllProperty($filter)
    {
        if (empty($filter)) {
            return Property::with("agency", "postCode", "type")->count();
        }

        if (array_key_exists("post_code_id", $filter)) {
            return Property::with("agency", "postCode", "type")
                ->where("post_code_id", "=", $filter["post_code_id"])->count();
        } else if (array_key_exists("county", $filter)) {
            return Property::with("agency", "postCode", "type")->whereHas("postCode", function ($query) use ($filter) {
                $query->where("county_id", "=", $filter["county"]);
            })->count();
        }
    }
    
    public function searchLocationByCountyAndPostCode($location)
    {
        return PostCode::with("county")
            ->where("code", "like", "%{$location}%", "or")
            ->orWhere("area", "like", "%{$location}%")
            ->orWhereHas("county", function ($query) use ($location){
                $query->where("name", "like", "%{$location}%");
            })
            ->orderBy("code")->get();
    }
    
    public function searchLocation($name)
    {
        return County::where("name", "like", "%{$name}%")->orderBy("name")->get();
    }

   
}

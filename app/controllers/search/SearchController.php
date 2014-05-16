<?php
namespace controllers\search;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

/**
 * Description of SearchController
 *
 * @author ndy40
 */
class SearchController extends \BaseController
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
}

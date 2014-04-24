<?php
namespace controllers\property;

use BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

/**
 * Controller for managing properties.
 *
 * @author ndy40
 */
class PropertyController extends BaseController
{
    protected $propertyLogic;
    
    public function __construct() {
        $this->propertyLogic = App::make("PropertyLogic");
    }

    public function index()
    {
        return View::make("property.property")->nest("filterForm", "forms.propertyfilter");
    }

    
    public function getCountries()
    {
        $countries = $this->propertyLogic->fetchCountries();
        return Response::json($countries);
    }

    public function getPostcode()
    {
        if (Cache::has("county_list")) {
            $counties = Cache::get("county_list");
        } else {
            $counties = $this->propertyLogic->fetchAllCounty();
        }
        $data = array();
        foreach ($counties as $county) {
            $data[$county->id] = $county->name;
        }
        return View::make("property.postcode", array("counties" => $data));
    }
    
    public function getPostCodesByCounty($id)
    {
//        if (Cache::has("fetch_county_" . $id)) {
//            $postcodes = Cache::get("fetch_county_" . $id);
//        } else {
            $county = $this->propertyLogic->fetchCounty($id);
            $postcodes = $county->postCodes->toArray();
//            Cache::put("fetch_county_" . $id, $postcodes, 3);
//        }
        
        return Response::json(array("data" => $postcodes), 200);
    }
    
}

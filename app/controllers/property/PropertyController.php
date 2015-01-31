<?php
namespace controllers\property;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use BaseController;

/**
 * Controller for managing properties.
 *
 * @author ndy40
 */
class PropertyController extends BaseController
{
    protected $propertyLogic;

    protected $agencyLogic;

    public function __construct()
    {
        $this->propertyLogic = App::make("PropertyLogic");
        $this->agencyLogic = App::make("AgentLogic");
    }

    public function index()
    {
        if (Cache::has("counties")) {
            $counties = Cache::get("counties");
        } else {
            $counties = $this->propertyLogic->fetchAllCounty();
            Cache::put("counties", $counties, 60);
        }

        $data = array("-1" => "All");
        foreach ($counties as $county) {
            $data[$county->id] = $county->name;
        }

        return View::make("property.property", array("county" => $data));
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
            Cache::put("county_list", $counties, 3);
        }
        $data = array();
        foreach ($counties as $county) {
            $data[$county->id] = $county->name;
        }
        return View::make("property.postcode", array("counties" => $data));
    }

    public function getPostCodesByCounty($id)
    {
        $postcodes = array();

        if (Cache::has("fetch_county_" . $id)) {
             $postcodes = Cache::get("fetch_county_" . $id);
        } elseif ($id != "-1") {
             $county = $this->propertyLogic->fetchCounty($id);
            if ($county) {
                $postcodes = $county->postCodes->toArray();
            }
            Cache::put("fetch_county_" . $id, $postcodes, 1);
        }
        return Response::json(array("data" => $postcodes), 200);
    }

    public function postDeletePostCode()
    {
        $data = Input::get("data");
        $id = $data["id"];
        $deleted = $this->propertyLogic->deletePostCode($id);

        return Response::json(array("data" => $deleted), 200);
    }

    public function postAddPostCode()
    {
        $data = Input::get("data");
        $countyId = $data["county"];
        $area = $data["area"];
        $code = $data["postcode"];
        $postCode = $this->propertyLogic->addPostCode($countyId, $code, $area);

        return Response::json(array("data" => $postCode), 200);
    }

    public function getFetchProperties()
    {
        $data = Input::get("data");
        $filter = array();
        if (array_key_exists("county", $data)) {
            if ($data["county"] != -1) {
                $filter["county"] = $data["county"];
            }
        }

        if (array_key_exists("post_code_id", $data)) {
            if ($data["post_code_id"] != null  && $data["post_code_id"] > 0) {
                $filter["post_code_id"] = $data["post_code_id"];
            }
        }

        $startIndex = array_key_exists("page", $data) ? $data["page"] : 1;
        $size = array_key_exists("size", $data) ? $data["size"] : (int)Config::get("view.pagination_size");

        $key = "admin_listing_"
            . implode('#', array_values($filter))
            . $startIndex
            . $size;

        if (Cache::has($key)) {
            $data = Cache::get($key);
        } else {
            $data  = $this->propertyLogic->fetchAllProperty($filter, $startIndex, $size);

            if (!$data->isEmpty()) {
                $data = $data->toArray();
            } else {
                $size = 0;
                $startIndex = 0;
            }
            Cache::put($key, $data, 1);
        }

        $count = $this->propertyLogic->countAllProperty($filter);

        return Response::json(array("count" => $count, "page"  => $startIndex, "size"  => $size, "data"  => $data));
    }
}

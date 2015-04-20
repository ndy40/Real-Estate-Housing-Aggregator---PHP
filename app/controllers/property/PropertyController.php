<?php

namespace controllers\property;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use models\entities\User;
use BaseController;

/**
 * Controller for managing properties.
 *
 * @author ndy40
 */
class PropertyController extends BaseController {

    protected $propertyLogic;
    protected $agencyLogic;

    public function __construct() {
        $this->propertyLogic = App::make("PropertyLogic");
        $this->agencyLogic = App::make("AgentLogic");
    }

    public function index() {
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

    public function getCountries() {
        $countries = $this->propertyLogic->fetchCountries();
        return Response::json($countries);
    }

    public function getPostcode() {
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

    public function getPostCodesByCounty($id) {
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

    public function postDeletePostCode() {
        $data = Input::get("data");
        $id = $data["id"];
        $deleted = $this->propertyLogic->deletePostCode($id);

        return Response::json(array("data" => $deleted), 200);
    }

    public function postAddPostCode() {
        $data = Input::get("data");
        $countyId = $data["county"];
        $area = $data["area"];
        $code = $data["postcode"];
        $postCode = $this->propertyLogic->addPostCode($countyId, $code, $area);

        return Response::json(array("data" => $postCode), 200);
    }

    public function getFetchProperties() {
        $data = Input::get("data");
        $filter = array();
        if (array_key_exists("county", $data)) {
            if ($data["county"] != -1) {
                $filter["county"] = $data["county"];
            }
        }

        if (array_key_exists("post_code_id", $data)) {
            if ($data["post_code_id"] != null && $data["post_code_id"] > 0) {
                $filter["post_code_id"] = $data["post_code_id"];
            }
        }

        $startIndex = array_key_exists("page", $data) ? $data["page"] : 1;
        $size = array_key_exists("size", $data) ? $data["size"] : (int) Config::get("view.pagination_size");

        $key = "admin_listing_"
                . implode('#', array_values($filter))
                . $startIndex
                . $size;

        if (Cache::has($key)) {
            $data = Cache::get($key);
        } else {
            $data = $this->propertyLogic->fetchAllProperty($filter, $startIndex, $size);

            if (!$data->isEmpty()) {
                $data = $data->toArray();
            } else {
                $size = 0;
                $startIndex = 0;
            }
            Cache::put($key, $data, 1);
        }

        $count = $this->propertyLogic->countAllProperty($filter);

        return Response::json(array("count" => $count, "page" => $startIndex, "size" => $size, "data" => $data));
    }

    /**
     * sendPropertyEmailToFriend method
     * this method is used to send email to friend(s) about property.
     *
     * @param int $propertyId
     * @return json
     */
    public function sendPropertyEmailToFriend($property_id) {
        $name = Input::get("name");
        $email = Input::get("email");
        $friendemails = explode(',', Input::get("friendemail"));
        $message = Input::get("message");

        $data = array('name' => $name, 'email' => $email, 'messages' => $message, 'property_id' => $property_id);
        //return View::make("emails.emailtofriend", $data);exit;
        \Mail::queue("emails.emailtofriend", $data, function ($message) use ($name, $email, $friendemails) {
                    $message->from($email, $name)->to($friendemails)->subject("Property Crunch | Property Detail");
                }
        );
        return Response::json(array("data" => 'success'), 200);
    }

    /**
     * sendPropertyRequestDetail method
     * this method is used to send request email to admin/agent about property.
     *
     * @param int $propertyId
     * @return json
     */
    public function sendPropertyRequestDetail($property_id) {
        $name = Input::get("name");
        $email = Input::get("email");
        $agentemail = Input::get("agentemail");
        $agentname = Input::get("agentname");
        $phone = Input::get("phone");
        $message = Input::get("message");

        // send to agent..
        $data = array(
            'name' => $name, 'email' => $email, 'messages' => $message,
            'phone' => $phone, 'property_id' => $property_id, 'recepientemail' => $agentemail, 'recepientname' => $agentname);
        \Mail::send("emails.requestdetail", $data, function ($message) use ($name, $email, $agentemail) {
                    $message->from($email, $name)->to($agentemail)->subject("Property Crunch | Property Request Detail");
                }
        );
        // send to default emails..
        if (Config::get('mail.default_email') != '') {
            foreach (Config::get('mail.default_email') as $defaultEmailKey => $defaultEmail) {
                $defaultEmailNameArray = Config::get('mail.default_email_name');
                $defaultEmailName = $defaultEmailNameArray[$defaultEmailKey];
                $dataDefault = array(
                    'name' => $name, 'email' => $email, 'messages' => $message, 'phone' => $phone, 'property_id' => $property_id,
                    'recepientemail' => $defaultEmail, 'recepientname' => $defaultEmailName);
                \Mail::send("emails.requestdetail", $dataDefault, function ($message) use ($name, $email, $defaultEmail, $defaultEmailName) {
                            $message->from($email, $name)->to($defaultEmail, $defaultEmailName)
                                    ->subject("Property Crunch | Property Request Detail");
                        }
                );
            }
        }
        return Response::json(array("data" => 'success'), 200);
    }

<<<<<<< HEAD
    /**
     * sendRetentionWeeklyEmailToUsers method
     * used to send properties weekly to users.
     *
     */
    public function sendRetentionWeeklyEmailToUsers() {
        $users = User::where('activated', '=', 1)->get();
        try {
            if (count($users) > 0) {
                $highestYieldProperties = $this->propertyLogic->getPropertiesByType('HighestYield');
                $highReductionProperties = $this->propertyLogic->getPropertiesByType('HighReduction');
                // preparing email message and sending..
                foreach ($users as $user) {
                    $name = $user->first_name . ' ' . $user->last_name;
                    $userEmail = $user->email;
                    $data = array('name' => $name, 'highestYieldProperties' => $highestYieldProperties, 'highReductionProperties' => $highReductionProperties);
                    if (count($highestYieldProperties) > 0 || count($highReductionProperties) > 0) {
                        \Mail::send("emails.retentionweekly", $data, function ($message) use ($userEmail, $name) {
                                    $message->to($userEmail, $name)->subject("Property Crunch | Properties matching your preference");
                                }
                        );
                    }
                }
            }
            return 'success';
        } catch (\Exception $ex) {
            return 'failed' . $ex->getMessage();
        }
    }

=======
>>>>>>> 733c0966eda6fde44f4982acf4f62f9918818978
}

<?php

namespace models\datalogic;

use models\entities\PropertyChangeLog;
use models\entities\SavedProperties;
use models\interfaces\DataLogicInterface;
use Illuminate\Support\Facades\App;
use models\interfaces\RepositoryInterface;

require_once("sparqllib.php");

/**
 * Property logic
 *
 * @author ndy40
 */
class PropertyLogic implements DataLogicInterface {

    protected $propertyRepo;

    public function __construct(RepositoryInterface $repository) {
        $this->propertyRepo = $repository;
    }

    public function fetchCountries() {
        return $this->propertyRepo->fetchCountries();
    }

    public function fetchAllCounty() {
        return $this->propertyRepo->fetchAllCounty();
    }

    public function fetchCounty($id) {
        return $this->propertyRepo->fetchCounty($id);
    }

    public function deleteCounty($id) {
        return $this->propertyRepo->deleteCounty($id);
    }

    public function addPostCode($countyId, $postcode, $area) {
        $county = $this->propertyRepo->fetchCounty($countyId);

        $postCode = new \models\entities\PostCode;
        $postCode->code = $postcode;
        $postCode->area = $area;

        $data = $postCode->county()->associate($county);
        return $this->propertyRepo->save($data);
    }

    /**
     * Delete post code
     * @param $id Database ID of post code.
     * @return mixed
     */
    public function deletePostCode($id) {
        return $this->propertyRepo->deletePostCode($id);
    }

    public function findProperty($id) {
        return $this->propertyRepo->fetch($id);
    }

    public function savePropertyChangeLog(PropertyChangeLog $prop) {
        $this->propertyRepo->savePropertyChangelog($prop);
    }

    public function fetchAllProperty($filter = array(), $startIndex = null, $size = null) {
        return $this->propertyRepo->fetchAllProperty($filter, $startIndex, $size);
    }

    public function countAllProperty($filter = array()) {
        return $this->propertyRepo->countAllProperty($filter);
    }

    public function searchLocationByCountyAndPostCode($location) {
        return $this->propertyRepo->searchLocationByCountyAndPostCode($location);
    }

    public function searchLocation($name) {
        return $this->propertyRepo->searchLocation($name);
    }

    public function findPropertySearchTypes($type_id) {
        return $this->propertyRepo->findPropertySearchTypes($type_id);
    }

    public function findPropertyDetailTypes($search_type_id) {
        return $this->propertyRepo->findPropertyDetailTypes($search_type_id);
    }

    public function findPropertyTypes($columnName = "name", $direction = "asc") {
        return $this->propertyRepo->findPropertyTypes($columnName, $direction);
    }

    /**
     * Method that searches the database for Properties. Will later user a SearchEngine.
     *
     * @param $filter
     * @param bool $isPublished
     * @param string $orderColumn
     * @param string $direction
     * @param int $startIndex
     * @param int $size
     * @param string $queryString
     * @return mixed
     */
    public function searchProperty(
    $filter = '', $isPublished = true, $orderColumn = "updated_at", $direction = "asc", $startIndex = 1, $size = 25, $queryString = array()
    ) {
        $query = array();

        // Build query string array before passing to PropertyResponsitory class.
        if (!empty($queryString)) {
            foreach ($queryString as $key => $value) {
                switch ($key) {
                    case 'price_min':
<<<<<<< HEAD
                        $query[] = array ("price", ">=", $value);
=======
                        $query[] = array("price", ">=", $value);
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
                        break;
                    case 'price_max':
                        $query[] = array("price", "<=", $value);
                        break;
                    case 'yield_min':
<<<<<<< HEAD
                        $query[] = array ("yield", ">=", $value);
=======
                        $query[] = array("yield", ">=", $value);
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
                        break;
                    case 'yield_max':
                        $query[] = array("yield", "<=", $value);
                        break;
<<<<<<< HEAD
                    case 'sort':
                        $sort = explode(" ", $value);
                        $orderColumn = $sort[0];
                        $direction   = $sort[1];
                        break;
                    default:
                       $query[] = array($key, "=", $value);
=======
                    case 'rooms_min':
                        $query[] = array("rooms", ">=", $value);
                        break;
                    case 'rooms_max':
                        $query[] = array("rooms", "<=", $value);
                        break;
                    case 'sort':
                        $sort = explode(" ", $value);
                        $orderColumn = $sort[0];
                        $direction = $sort[1];
                        break;
                    default:
                        $query[] = array($key, "=", $value);
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
                }
            }
        }

        return $this->propertyRepo->searchProperty(
                        $filter, $isPublished, $orderColumn, $direction, $startIndex, $size, $query
        );
    }

    public function searchPropertyCount(
<<<<<<< HEAD
        $filter = '',
        $isPublished = true,
        $queryString = array()
=======
    $filter = '', $isPublished = true, $queryString = array()
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
    ) {
        $query = array();

        // Build query string array before passing to PropertyResponsitory class.
        if (!empty($queryString)) {
            unset($queryString["sort"]);
            foreach ($queryString as $key => $value) {
                if ($key === 'price_min') {
                    $query[] = array("price", ">=", $value);
                } else if ($key === 'price_max') {
                    $query[] = array("price", "<=", $value);
                } else if ($key === 'yield_max') {
                    $query[] = array("yield", ">=", $value);
                } else if ($key === 'yield_min') {
                    $query[] = array("yield", "<=", $value);
<<<<<<< HEAD
=======
                } else if ($key === 'rooms_min') {
                    $query[] = array("rooms", ">=", $value);
                } else if ($key === 'rooms_max') {
                    $query[] = array("rooms", "<=", $value);
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
                } else {
                    $query[] = array($key, "=", $value);
                }
            }
        }

        return $this->propertyRepo->searchPropertyCount($filter, $isPublished, $query);
    }

    public function getAvgRoomPricesByCounty($countyId) {
        return $this->propertyRepo->getAvgRoomPricesByCounty($countyId);
    }

    public function fetchPostCodeByName($areaName) {
        return $this->propertyRepo->fetchPostCodeByName($areaName);
    }

    public function fetchPostCode($postcode) {
        return $this->propertyRepo->fetchPostCode($postcode);
    }

    public function find($id) {
        return $this->propertyRepo->fetch($id);
    }

    public function save($entity) {
        return $this->propertyRepo->save($entity);
    }

<<<<<<< HEAD
    public function saveUserProperty($userId, $propertyId, $calculations)
    {
=======
    public function saveUserProperty($userId, $propertyId, $calculations) {
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
        $authLogic = App::make("AuthLogic");
        $user = $authLogic->findUser($userId);
        $property = $this->propertyRepo->fetch($propertyId);
        $savedProperty = new SavedProperties(array("calculations" => $calculations));
        $savedProperty->user()->associate($user);
        $savedProperty->property()->associate($property);

        return $this->propertyRepo->save($savedProperty);
    }

    public function deleteImage($id) {
        $this->propertyRepo->deleteImage($id);
    }

    /**
     * Compute the Rental Yield for Property in a Post Code Area.
     *
     * @param integer $post_code_id
     * @param integer $type_id
     * @param double $num_rooms
     */
<<<<<<< HEAD
    public function getAreaRentalYield($post_code_id, $rooms, $type_id)
    {
        $avgRental = $this->getAveragePrice($post_code_id, $rooms, $type_id, "rent");
        $avgSalesPrice = $this->getAveragePrice($post_code_id, $rooms, $type_id, "sale");

        $yield = (($avgRental * 12)/$avgSalesPrice) * 100;
=======
    public function getAreaRentalYield($post_code_id, $rooms, $type_id) {
        $avgRental = $this->getAveragePrice($post_code_id, $rooms, $type_id, "rent");
        $avgSalesPrice = $this->getAveragePrice($post_code_id, $rooms, $type_id, "sale");

        $yield = (($avgRental * 12) / $avgSalesPrice) * 100;
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501

        return $yield;
    }

    /**
     * Compute the Rental Yield of a Property.
     *
     * @param int $id
     * @return float
     */
<<<<<<< HEAD
    public function getRentalYieldOfProperty($id)
    {
=======
    public function getRentalYieldOfProperty($id) {
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
        $property = $this->propertyRepo->fetch($id);

        $post_code_id = $property->postCode->id;
        $rooms = $property->rooms;
        $type_id = $property->type_id;

        //compute annual rental yiled
        $avg = $this->getAveragePrice($post_code_id, $rooms, $type_id, "rent") * 12;

<<<<<<< HEAD
        $yield = ($avg/$property->price) * 100;
=======
        $yield = ($avg / $property->price) * 100;
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501

        return $yield;
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
    public function getAveragePrice(
<<<<<<< HEAD
        $post_code_id,
        $rooms,
        $type_id,
        $offerType = 'sale'
    ) {
        return $this->propertyRepo->getAveragePrice(
            $post_code_id,
            $rooms,
            $type_id,
            $offerType
        );
    }


    public function getPropertyByIds(array $ids) {
        return $this->propertyRepo->getPropertyByIds($ids);
    }
=======
    $post_code_id, $rooms, $type_id, $offerType = 'sale'
    ) {
        return $this->propertyRepo->getAveragePrice(
                        $post_code_id, $rooms, $type_id, $offerType
        );
    }

    public function getPropertyByIds(array $ids) {
        return $this->propertyRepo->getPropertyByIds($ids);
    }

    public function getComparableProperties($propertyId) {
        $address = $this->getGooAddressById($propertyId);

        $county = strtoupper($address['county']);
        $town = strtoupper($address['town']);
        $locality = strtoupper($address['locality']);
        $street = strtoupper($address['street']);
        $postcode = $address['postcode'];
        $location = $address['location'];

        $db = sparql_connect("http://landregistry.data.gov.uk/landregistry/query");
        if (!$db) {
            print sparql_errno() . ": " . sparql_error() . "\n";
            exit;
        }


        sparql_ns("common", "http://landregistry.data.gov.uk/def/common/");
        sparql_ns("ppi", "http://landregistry.data.gov.uk/def/ppi/");
        sparql_ns("rdf", "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
        sparql_ns("xsd", "http://www.w3.org/2001/XMLSchema#");

        if (strlen($postcode) <= 4)
            $sparql_postcode = '';
        else
            $sparql_postcode = '?___propertyAddress_0 common:postcode "' . $postcode . '"^^<http://www.w3.org/2001/XMLSchema#string> .';

        $sparql = 'SELECT ?item
            WHERE {
            {
                ?___propertyAddress_0 common:town "' . $town . '"^^<http://www.w3.org/2001/XMLSchema#string> .
                ?___propertyAddress_0 common:locality "' . $locality . '"^^<http://www.w3.org/2001/XMLSchema#string> .' .
                '?item ppi:propertyAddress ?___propertyAddress_0 .
                ?item ppi:transactionDate ?___transactionDate .
                ?item rdf:type ppi:TransactionRecord .
            } } ORDER BY DESC(?___transactionDate) OFFSET 0 LIMIT 5';
        //FILTER (REGEX(SUBSTR(?___postcode,1,4) , "' . $postcode . '", "i"))
        // UNION {
        // ?___propertyAddress_0 common:town "'.$town.'"^^<http://www.w3.org/2001/XMLSchema#string> .
        // ?___propertyAddress_0 common:street "'.$street.'"^^<http://www.w3.org/2001/XMLSchema#string> .'.
        // $sparql_postcode .
        // '?item ppi:propertyAddress ?___propertyAddress_0 .
        // ?item ppi:transactionDate ?___transactionDate .
        // ?item rdf:type ppi:TransactionRecord .
        // }
        $result = sparql_query($sparql);

        $comparables = array();
        while ($row = sparql_fetch_array($result)) {
            $comparables[] = $row['item'];
        }

        // return $comparables;

        $data = array();
        foreach ($comparables as $comparable) {
            try {
                $arr = array();
                $comparable_info = file_get_contents($comparable . '.json');
                $json = json_decode($comparable_info, true);

                $arr['price'] = $json['result']['primaryTopic']['pricePaid'];

                $addr = $json['result']['primaryTopic']['propertyAddress'];
                $paon = array_key_exists('paon', $addr) ? $addr['paon'] . ' ' : '';
                $street = array_key_exists('street', $addr) ? $addr['street'] . ',' : '';
                $town = array_key_exists('town', $addr) ? $addr['town'] . ',' : '';
                $locality = array_key_exists('locality', $addr) ? $addr['locality'] . ',' : '';
                $district = array_key_exists('district', $addr) ? $addr['district'] . ',' : '';
                $county = array_key_exists('county', $addr) ? $addr['county'] : '';
                $arr['address'] = $paon . $street . $town . $locality . $district . $county;

                $arr['transactionDate'] = $json['result']['primaryTopic']['transactionDate'];

                $postcode = $addr['postcode'];
                // $addr = str_replace(',', ' ', $arr['address']);
                $latlng = $this->getLatLngByAddr($arr['address']);
                if ($latlng === 'ZERO_RESULTS')
                    $latlng = $this->getLatLngByAddr($street . $town . $locality . $district . $county);
                $arr['distance'] = $this->Haversine($location, $latlng);

                $data[] = $arr;
            } catch (\Exception $ex) {
                return $data;
            }
        }

        return $data;
    }

    public function getGooAddressById($propertyId) {
        $property = $this->propertyRepo->fetch($propertyId);
        $address = str_replace(" ", "+", $property->address);

        $location = $this->getLatLngByAddr($address);

        $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?latlng=" . $location[0] . "," . $location[1] . "&sensor=false");
        $json = json_decode($json);

        $addr_comp = $json->results[0]->address_components;
        $street = $locality = $town = $county = '';
        foreach ($addr_comp as $addr) {
            $addr_type = $addr->types;
            $addr_name = $addr->long_name;
            if (in_array('route', $addr_type))
                $street = $addr_name;
            else if (in_array('locality', $addr_type))
                $locality = $addr_name;
            else if (in_array('postal_town', $addr_type))
                $town = $addr_name;
            else if (in_array('administrative_area_level_1', $addr_type) || in_array('administrative_area_level_2', $addr_type))
                $county = $addr_name;
            else if (in_array('postal_code', $addr_type))
                $postcode = $addr_name;
        }

        return array('street' => $street, 'locality' => $locality, 'town' => $town, 'county' => $county, 'postcode' => $postcode, 'location' => $location);
    }

    public function getLatLngByAddr($address) {
        $address = str_replace(" ", "+", $address);
        $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=" . $address . "&sensor=false");
        $json = json_decode($json);

        if ($json->status === "ZERO_RESULTS")
            return "ZERO_RESULTS";

        $lat = $json->results[0]->geometry->location->lat;
        $lng = $json->results[0]->geometry->location->lng;

        return array($lat, $lng);
    }

    public function Haversine($start, $finish) {

        $theta = $start[1] - $finish[1];
        $distance = (sin(deg2rad($start[0])) * sin(deg2rad($finish[0]))) + (cos(deg2rad($start[0])) * cos(deg2rad($finish[0])) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;

        return round($distance, 2) * 1.609344;
    }

    public function getPropertiesByType($type, $recordCount = 3) {
        return $this->propertyRepo->getPropertiesByType($type, $recordCount);
    }

    public function getAutoCompleteSuggestion($search, $recordCount) {
        return $this->propertyRepo->getAutoCompleteSuggestion($search, $recordCount);
    }

>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
}

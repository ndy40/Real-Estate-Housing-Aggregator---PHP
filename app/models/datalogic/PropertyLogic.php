<?php
namespace models\datalogic;

use models\entities\PropertyChangeLog;
use models\entities\SavedProperties;
use models\interfaces\DataLogicInterface;
use Illuminate\Support\Facades\App;
use models\interfaces\RepositoryInterface;

/**
 * Property logic
 *
 * @author ndy40
 */
class PropertyLogic implements DataLogicInterface
{
    protected $propertyRepo;

    public function __construct(RepositoryInterface $repository)
    {
        $this->propertyRepo = $repository;
    }

    public function fetchCountries ()
    {
        return $this->propertyRepo->fetchCountries();
    }

    public function fetchAllCounty()
    {
        return $this->propertyRepo->fetchAllCounty();
    }

    public function fetchCounty($id)
    {
        return $this->propertyRepo->fetchCounty($id);
    }

    public function deleteCounty($id)
    {
        return $this->propertyRepo->deleteCounty($id);
    }

    public function addPostCode($countyId, $postcode, $area)
    {
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
    public function deletePostCode($id)
    {
        return $this->propertyRepo->deletePostCode($id);
    }

    public function findProperty ($id)
    {
        return $this->propertyRepo->fetch($id);
    }

    public function savePropertyChangeLog (PropertyChangeLog $prop)
    {
        $this->propertyRepo->savePropertyChangelog($prop);
    }

    public function fetchAllProperty($filter = array (), $startIndex = null, $size = null)
    {
        return $this->propertyRepo->fetchAllProperty($filter, $startIndex, $size);
    }

    public function countAllProperty($filter = array())
    {
        return $this->propertyRepo->countAllProperty($filter);
    }

    public function searchLocationByCountyAndPostCode($location)
    {
        return $this->propertyRepo->searchLocationByCountyAndPostCode($location);
    }

    public function searchLocation($name)
    {
        return $this->propertyRepo->searchLocation($name);
    }

    public function findPropertyTypes($columnName = "name", $direction = "asc")
    {
        return $this->propertyRepo->findPropertyTypes($columnName, $direction);
    }

    public function searchProperty(
        $filter,
        $isPublished = true,
        $orderColumn = "updated_at",
        $direction = "asc",
        $startIndex = 1,
        $size = 25
    ) {
        return $this->propertyRepo->searchProperty($filter, $isPublished, $orderColumn, $direction, $startIndex, $size);
    }

    public function searchPropertyCount(
        $filter,
        $isPublished = true
    ) {
        return $this->propertyRepo->searchPropertyCount($filter, $isPublished);
    }

    public function getAvgRoomPricesByCounty($countyId)
    {
        return $this->propertyRepo->getAvgRoomPricesByCounty($countyId);
    }

    public function fetchPostCodeByName($areaName)
    {
        return $this->propertyRepo->fetchPostCodeByName($areaName);
    }

    public function find($id)
    {
        return $this->propertyRepo->fetch($id);
    }

    public function saveUserProperty($userId, $propertyId, $calculations)
    {
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
}

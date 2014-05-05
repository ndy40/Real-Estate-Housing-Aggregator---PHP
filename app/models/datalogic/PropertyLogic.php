<?php
namespace models\datalogic;

use models\interfaces\DataLogicInterface;
use Illuminate\Support\Facades\App;

/**
 * Property logic
 *
 * @author ndy40
 */
class PropertyLogic implements DataLogicInterface 
{
    protected $propertyRepo;
    
    public function __construct() 
    {
        $this->propertyRepo = App::make("PropertyRepository");
    }
    
    public function fetchCountries ()
    {
        return $this->propertyRepo->fetchCountries();
    }

    public function fetchAllCounty(){
        return $this->propertyRepo->fetchAllCounty();
    }
    
    public function fetchCounty($id) {
        return $this->propertyRepo->fetchCounty($id);
    }
    
    public function deleteCounty($id) {
        return $this->propertyRepo->deleteCounty($id);
    }
    
    public function addPostCode ($countyId, $postcode, $area) {
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

    public function findProperty ($id)
    {
        return $this->propertyRepo->
    }


}

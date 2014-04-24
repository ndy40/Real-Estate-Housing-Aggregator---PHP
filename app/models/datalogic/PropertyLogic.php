<?php
namespace models\datalogic;

use models\interfaces\DataLogicInterface;
use Illuminate\Support\Facades\App;
use models\entities\County;

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
}

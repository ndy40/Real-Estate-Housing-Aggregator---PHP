<?php
namespace controllers\property;

use BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

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
        return "index";
    }
    
    public function country ()
    {
        return View::make("property.property")->nest("filterForm", "forms.propertyfilter");
    }
    
    public function getCountries()
    {
        $countries = $this->propertyLogic->fetchCountries();
        return Response::json($countries);
    }
    
}

<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace models\factory;

use models\interfaces\FactoryInterface;
use models\entities\FailedScrapes;
use models\entities\Property;

/**
 * This is a factory class for creating Entity Objects throughout the system.
 *
 * @author Ndifreke Ekott <ndy40.ekott@gmail.com>
 * @package models\factory
 */
class EntityFactory implements FactoryInterface
{
    /**
     * Method for creating new Property objects. 
     * 
     * @param array $attributes An associative array of properties to set for a class.
     * @return Property An instance of the models\entities\Property class. 
     */
    public function createProperty($attributes = null) 
    {
        $property = null;
        if (empty($attributes)) {
            $property = new Property();
        } else {
            $property = new Property($attributes);
        }
        
        return $property;
    }
    
    /**
     * This is a convenient method for created a FailedScrape object.
     * 
     * @param array $attributes Attributes to initialize the Object with. 
     * @return \models\entities\FailedScrapes
     */
    public function createFailedScrapes(array $attributes)
    {
        if (!isset($attributes)) {
            $failedScrape =  new FailedScrapes();
        } else {
            $failedScrape = new FailedScrapes($attributes);
        }
        
        return $failedScrape;
    }
}

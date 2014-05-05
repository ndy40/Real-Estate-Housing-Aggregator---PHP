<?php
/**
 * Created by PhpStorm.
 * User: ndy40
 * Date: 05/05/2014
 * Time: 10:33
 */

namespace models\entities\observers;

/**
 * Observer for all property related events.
 * Class PropertyObserver
 * @package models\entities\observers
 */
class PropertyObserver
{
    public function updating ($property)
    {
        $propRepo = App::make("PropertyLogic");
        $property = $propRepo->fetch($property->id);
    }

} 
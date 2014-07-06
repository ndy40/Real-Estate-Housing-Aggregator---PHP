<?php
/**
 * Created by PhpStorm.
 * User: ndy40
 * Date: 05/05/2014
 * Time: 12:21
 *
 * A collection of events to be handled throughout the system.
 */

use models\entities\Property;

//////////////////////////////
//////// Model Events ///////
////////////////////////////

Property::observe(new \models\entities\observers\PropertyObserver);
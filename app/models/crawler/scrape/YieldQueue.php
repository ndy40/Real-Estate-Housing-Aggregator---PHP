<?php
/*
 * Job class for calculating Rental yield.
 */

namespace models\crawler\scrape;

use models\crawler\abstracts\JobQueue;
use Illuminate\Support\Facades\App;


/**
 * Description of YieldQueue
 *
 * @author ndy40
 */
class YieldQueue extends JobQueue
{
    public function fire($job, $data) {
        $propertyLogic = App::make("PropertyLogic");
        foreach ($data as $property) {
            $yield = $propertyLogic->getRentalYieldOfProperty($property["id"]);
            $entity = $propertyLogic->find($property["id"]);
			if ($yield !== 0 && $entity->yield !== $yield) {
				echo 'save' . $property["id"];
            	$entity->yield = $yield;
            	$saved = $propertyLogic->save($entity);
			}
        }
        $job->delete();
    }

}

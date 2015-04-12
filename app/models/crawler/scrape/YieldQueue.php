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
<<<<<<< HEAD
            $entity->yield = $yield;
            $saved = $propertyLogic->save($entity);
=======
			if ($yield !== 0 && $entity->yield !== $yield) {
				echo 'save' . $property["id"];
            	$entity->yield = $yield;
            	$saved = $propertyLogic->save($entity);
			}
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
        }
        $job->delete();
    }

}

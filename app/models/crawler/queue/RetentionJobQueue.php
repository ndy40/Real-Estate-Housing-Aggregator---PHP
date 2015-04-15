<?php

/*
 * Job class for calculating Rental yield.
 */

namespace models\queue;

use models\abstracts\JobQueue;
use Illuminate\Support\Facades\App;

/**
 * Description of YieldQueue
 *
 * @author ndy40
 */
class RetentionJobQueue extends JobQueue {

    /**
     * Property logic class.
     * 
     * @var PropertyLogic
     */
    protected $propertyLogic;
    
    public function fire($job, $data) {
        
        $highestYieldProperties = $this->propertyLogic->getPropertiesByType('HighestYield');
        $highReductionProperties = $this->propertyLogic->getPropertiesByType('HighReduction');
        
        $job->delete();
    }

}

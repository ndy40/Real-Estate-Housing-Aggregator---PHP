<?php
/**
 * Created by PhpStorm.
 * User: ndy40
 * Date: 17/04/15
 * Time: 23:37
 */

namespace models\crawler\queue;

use models\crawler\abstracts\JobQueue;
use Predis\NotSupportedException;

/**
 * A class that collects all Email related notifications.
 * @package models\crawler\queue
 */
class EmailQueue extends JobQueue{
    /**
     * Default method called by queuing system,
     *
     * @params Job $job
     * @param mixed[] $data
     */
    public function fire($job, $data)
    {
        throw new NotSupportedException("This method is not supported");
    }


}

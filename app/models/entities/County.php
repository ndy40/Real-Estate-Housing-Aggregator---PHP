<?php
namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Description of County
 *
 * @author ndy40
 */
class County extends Ardent
{
    public function postCodes () {
        return $this->hasMany('PostCode');
    }
}

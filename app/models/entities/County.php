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
    protected $table = "county";
    protected $hidden = array("created_at", "updated_at");


    public function postCodes()
    {
        return $this->hasMany('\\models\\entities\\PostCode');
    }
}

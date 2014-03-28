<?php
namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Description of Agency
 *
 * @author ndy40
 */
class Agency extends Ardent
{
    public static $rules = array (
        'name'      => 'required|alpha_num',
        'crawler'   => 'required|alpha_num'
    );
    
    public function country () {
        return $this->belongsTo('Country');
    }
    
    public function catalogues () {
        return $this->hasMany('Catalogue');
    }
}

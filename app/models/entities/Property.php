<?php
namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Description of Property
 *
 * @author ndy40
 */
class Property extends Ardent
{
    public static $rules = array (
        'marketer'  => 'required|alpha_num',
        'phone'     => 'required',
        'rooms'     => 'numeric',
        'address'   => 'required|alpha_num',
        'price'     => 'numeric',
        'url'       => 'required|url',
    );
    
    public function postCode () {
        return $this->belongsTo('PostCode');
    }
    
}

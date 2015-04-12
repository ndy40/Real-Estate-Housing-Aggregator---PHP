<?php
namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Description of PropertyType
 *
 * @author ndy40
 */
class PropertyType extends Ardent
{
    protected $table = 'type';
	public $timestamps = false;
<<<<<<< HEAD
=======

    public function searchType() {
        return $this->belongsTo('\\models\\entities\\PropertySearchType');
    }
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
}

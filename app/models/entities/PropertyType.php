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

    public function searchType() {
        return $this->belongsTo('\\models\\entities\\PropertySearchType');
    }
}

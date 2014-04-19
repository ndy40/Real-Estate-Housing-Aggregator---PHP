<?php
namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Description of Catalogue
 *
 * @author ndy40
 */
class Catalogue extends Ardent
{
    protected $table = "catalogue";
    
    public $timestamps = false;
    
    public static $rules = array (
        'url' => 'required|url',
    );
    
    public function agency()
    {
        return $this->belongsTo("Agency");
    }
}

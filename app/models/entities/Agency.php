<?php
namespace models\entities;

use LaravelBook\Ardent\Ardent;
use models\interfaces\MassAssignInterface;

/**
 * Description of Agency
 *
 * @author ndy40
 */
class Agency extends Ardent implements MassAssignInterface
{
    protected $table = 'agency';
    
    public static $rules = array (
        'name'      => 'required|alpha_num',
        'crawler'   => 'required|alpha_num'
    );
    
    public function country()
    {
        return $this->belongsTo('\\models\\entities\\Country', 'country_id');
    }
    
    public function catalogues()
    {
        return $this->hasMany('\\models\\entities\\Catalogue');
    }
    
    public function failedScrapes()
    {
        return $this->hasMany('\\models\\entities\\FailedScrapes');
    }

    public function assignAttributes($attribute = array())
    {
        if (!is_array($attribute)) {
            throw new \Exception(
                "Mass assign attribute must be an array." . get_class($this)
            );
        }
        
        foreach ($attribute as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }
}

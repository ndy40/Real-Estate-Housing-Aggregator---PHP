<?php
namespace models\entities;

use LaravelBook\Ardent\Ardent;
use models\interfaces\MassAssignInterface;

/**
 * Description of Property
 *
 * @property string address
 * @author ndy40
 */
class Property extends Ardent implements MassAssignInterface
{
    protected $table = 'properties';
    
    public static $rules = array (
        'marketer'  => 'required',
        'phone'     => 'required',
        'rooms'     => 'numeric',
        'address'   => 'required',
        'price'     => 'numeric',
<<<<<<< HEAD
        'url'       => 'required|url',
        'offer_type'=> 'required|in:Sale,Rent'
=======
//        'url'       => 'required|url',
	'url'       => '',
//        'offer_type'=> 'required|in:Sale,Rent'

>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
    );
    
    protected $fillable = array('marketer', 'rooms', 'url', 'address', 'price',
        'hash', 'available', 'published', "offer_type"
    );

    protected $hidden = array("hash");
    
    public function postCode()
    {
        return $this->belongsTo('\\models\\entities\\PostCode');
    }
    
    public function agency()
    {
        return $this->belongsTo('\\models\\entities\\Agency');
    }
    
    public function type()
    {
        return $this->belongsTo('\\models\\entities\\PropertyType', 'type_id');
    }

    public function history()
    {
        return $this->hasMany("\\models\\entities\\PropertyChangeLog");
    }

    public function assignAttributes($attribute = array())
    {
        foreach ($attribute as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }
    
    public function images() {
        return $this->hasMany("\\models\\entities\\Image", "property_id");
    }
}

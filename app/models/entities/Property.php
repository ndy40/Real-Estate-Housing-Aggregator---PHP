<?php
namespace models\entities;

use LaravelBook\Ardent\Ardent;
use models\interfaces\MassAssignInterface;
use Illuminate\Database\Eloquent\Model;


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
        'url'       => 'required|url',
    );
    
    protected $fillable = array('marketer', 'rooms', 'url', 'address', 'price',
        'hash', 'available', 'published',
    );

    protected $hidden = array("hash");
    
    public function postCode () {
        return $this->belongsTo('\models\entities\PostCode');
    }
    
    public function agency()
    {
        return $this->belongsTo('\models\entities\Agency');
    }
    
    public function type()
    {
        return $this->belongsTo('\models\entities\PropertyType', 'type_id');
    }

    public function getOfferTypeAttribute($offerType)
    {
        $this->attributes["offer_type"] = ucwords($offerType);
    }

    public function assignAttributes($attribute = array()) {
        foreach($attribute as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

}

<<<<<<< HEAD
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
        return $this->belongsTo("\\models\\entities\\Agency");
    }
}
=======
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
        //'url' => 'required|url',
    );
    
    public function agency()
    {
        return $this->belongsTo("\\models\\entities\\Agency");
    }
}
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501

<?php
namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Description of SavedProperties
 *
 * @author ndy40
 */
class SavedProperties extends Ardent
{
    protected $table = "saved_properties";

    protected $fillable = array("calculations");

    public $timestamps = false;

    public function property()
    {
        return $this->belongsTo("\models\entities\Property");
    }

    public function user()
    {
        return $this->belongsTo("\models\entities\User");
    }
}

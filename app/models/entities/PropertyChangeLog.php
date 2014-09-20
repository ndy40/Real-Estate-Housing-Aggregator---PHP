<?php
namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Class representing the change history of property
 *
 * @author ndy40
 */
class PropertyChangeLog extends Ardent
{

    protected $hidden = array("property_id", "created_at", "id");

    public function property()
    {
        return $this->belongsTo("\\models\\entities\\Property");
    }
}

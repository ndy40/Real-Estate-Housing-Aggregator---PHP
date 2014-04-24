<?php
namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Description of PropertyChangeLog
 *
 * @author ndy40
 */
class PropertyChangeLog extends Ardent
{
    
    
    public function property()
    {
        return $this->belongsTo("\models\entities\Property");
    }
}

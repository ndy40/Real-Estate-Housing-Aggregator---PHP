<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\events;

use \models\events\ModelEventObserverAbstract;

/**
 * Description of PropertyObserver
 *
 * @author ndy40
 */
class PropertyObserver extends ModelEventObserverAbstract
{
    public function saving($model)
    {
        parent::saving($model);
    }
    
    public function updating($model)
    {
        parent::updating($model);
    }
}

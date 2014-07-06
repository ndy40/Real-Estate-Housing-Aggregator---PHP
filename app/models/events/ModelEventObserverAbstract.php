<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\events;

/**
 * Description of ModelEvenAbstract
 *
 * @author ndy40
 */
abstract class ModelEventObserverAbstract
{
    /**
     * Event method that is called when an entity is about to be saved.
     * @param Object $model
     */
    public function saving($model)
    {

    }
    /**
     * Event method called when an entity is about to be updated.
     * @param Object $model
     */
    public function updating($model)
    {

    }
}

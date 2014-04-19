<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\entities;

use models\interfaces\MassAssignInterface;

/**
 * Extends Sentry Cartalyst base User class. Adds specific relationships.
 *
 * @author ndy40
 */
class User extends \Cartalyst\Sentry\Users\Eloquent\User implements MassAssignInterface
{
//    protected $hidden = array('password');
    
    public function assignAttributes($attribute = array()) {
        foreach ($attribute as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }
    
    public function getFullName () {
        return $this->first_name . " " . $this->last_name;
    }
    
}

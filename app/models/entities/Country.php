<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Description of Country
 *
 * @author ndy40
 */
class Country extends Ardent
{
    public static $rules = array (
        'name'      => 'required|alpha',
        'code'      => 'required|alpha|min:2|max:3',
        'currency'  => 'required|alpha',
    );
    
    public function agencies () {
        return $this->hasMany('Agency');
    }
    
    
}

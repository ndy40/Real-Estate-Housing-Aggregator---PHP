<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Description of PostCode
 *
 * @author ndy40
 */
class PostCode extends Ardent 
{
    protected $table = "post_codes";
    
    public function county () 
    {
        return $this->belongsTo('models\entities\County');
    }
    
    public function properties ()
    {
        return $this->hasMany('Property', 'post_code');
    }
    
}

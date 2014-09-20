<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Description of Image
 *
 * @author ndy40
 */
class Image extends Ardent 
{
    protected $table = "images";
    
    public static $rules = array(
        "image" => "required",
    );
    
    protected $fillable = array ("image", "thumb", "enabled", "basename");
    
    public $timestamps = false;
    
//    public function property(){
//        return $this->belongsTo("\\models\\entities\\Property", "property_id");
//    }
}

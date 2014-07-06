<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Description of FailedScrapes
 *
 * @author ndy40
 */
class FailedScrapes extends Ardent
{
    
    protected $table = 'failed_scrapes';
    
    protected $fillable = array ('results', "message", "data");
    
    public function agency()
    {
        return $this->belongsTo("\models\entities\Agency");
    }
}

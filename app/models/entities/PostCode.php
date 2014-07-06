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

    protected $hidden = array("created_at", "updated_at");

    protected $fillable = array("area", "code");

    public function county ()
    {
        return $this->belongsTo('models\entities\County');
    }

    public function properties ()
    {
        return $this->hasMany('models\entities\Property', 'post_code_id');
    }
}

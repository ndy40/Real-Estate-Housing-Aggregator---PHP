<?php
/**
 * Created by PhpStorm.
 * User: ndy40
 * Date: 06/05/15
 * Time: 00:36
 */

namespace models\entities;

use LaravelBook\Ardent\Ardent;

class DataExport extends Ardent
{
    protected $table = 'dataexport';

    protected $fillable = array('export_id', 'xml', 'publish');

    public $timestamps = false;
}

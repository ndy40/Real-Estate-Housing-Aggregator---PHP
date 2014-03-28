<?php
namespace models\entities;

use LaravelBook\Ardent\Ardent;

/**
 * Description of Catalogue
 *
 * @author ndy40
 */
class Catalogue extends Ardent
{
    public static $rules = array (
        'url' => 'required|url',
    );
}

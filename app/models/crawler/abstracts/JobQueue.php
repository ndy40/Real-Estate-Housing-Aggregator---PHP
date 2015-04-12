<?php
/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace models\crawler\abstracts;

    
/**
 * Description of JobQueue
 *
 * @author Ndifreke Ekott <ndy40.ekott@gmail.com>
 * @package models\crawler
 */
abstract class JobQueue
{

    const ITEM_NOT_AVAILABLE = 'notAvailable';
    const ITEM_AVAILABLE = 'available';
    const ITEM_REMOVED = 'removed';
    const ITEM_PROPERTY_NOT_SUPPORTED = 'notSupported';
    
    /**
     * Default method called by queuing system,
     * 
     * @params Job $job
     * @param mixed[] $data
     */
    abstract public function fire ($job, $data);
}

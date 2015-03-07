<?php
/**
 * This is an Abstract class all Scrape related classes will extend.
 */
namespace models\crawler\abstracts;

use models\exceptions\ScrapeInitializationException;

/**
 * Description of Scrape
 *
 * @author Kaimin
 * @package models\crawler\abstracts
 *
 */
abstract class FeedAbstract
{
    abstract public function saveXMLfromBLM($blmpath, $xmlpath, $publish);
}
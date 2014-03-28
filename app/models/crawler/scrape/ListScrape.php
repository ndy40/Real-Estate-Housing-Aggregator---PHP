<?php
namespace models\crawler\scrape;

use models\crawler\abstracts\Scrape;
use Exception;
/**
 * Description of ListScrape
 *
 * @author ndy40
 */
class ListScrape extends Scrape
{
    protected function checkForErrors($result) 
    {
        echo $result;
    }

    protected function handleResult($result) 
    {
        return $result;
    }

}

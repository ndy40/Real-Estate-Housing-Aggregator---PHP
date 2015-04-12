<?php
namespace models\crawler\scrape;

use models\crawler\abstracts\ScrapeAbstract;

/**
 * Description of ListScrape
 *
 * @author ndy40
 */
class ListScrape extends ScrapeAbstract
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

<?php
namespace models\crawler\scrape;

use models\crawler\abstracts\ScrapeAbstract;

/**
 * Description of DataScrape
 *
 * @author kaimin
 */
class FeedScrape extends ScrapeAbstract
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

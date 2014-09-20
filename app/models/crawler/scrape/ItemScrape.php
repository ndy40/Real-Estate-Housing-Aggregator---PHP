<?php
namespace models\crawler\scrape;

use models\crawler\abstracts\ScrapeAbstract;

/**
 * Description of ItemScrape
 *
 * @author ndy40
 */
class ItemScrape extends ScrapeAbstract
{
    protected function checkForErrors($result)
    {
        return $result;
    }

    protected function handleResult($result)
    {
        return $result;
    }
}

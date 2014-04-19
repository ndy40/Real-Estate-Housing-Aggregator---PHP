<?php
namespace models\crawler\scrape;

use models\crawler\abstracts\Scrape;

/**
 * Description of ItemScrape
 *
 * @author ndy40
 */
class ItemScrape extends Scrape
{
    protected function checkForErrors($result) {
        return $result;
    }

    protected function handleResult($result) {
        return $result;
    }

}

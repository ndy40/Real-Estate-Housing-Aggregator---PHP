<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\datalogic;

use models\interfaces\DataLogicInterface;
use Illuminate\Support\Facades\App;

/**
 * Description of ScrapeLogic
 *
 * @author ndy40
 */
class ScrapeLogic implements DataLogicInterface
{
    protected $scrapeRepo;

    public function __construct()
    {
        $this->scrapeRepo = App::make("ScrapeRepository");

    }
    public function fetchAllFailedScrapes ()
    {
        return $this->scrapeRepo->fetchAllFailedScrapes();
    }

    public function deleteFailedScrape($id)
    {
        return $this->scrapeRepo->deleteFailedScrape($id);
    }


}

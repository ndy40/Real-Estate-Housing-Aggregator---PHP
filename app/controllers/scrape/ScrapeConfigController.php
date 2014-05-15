<?php
namespace controllers\scrape;

use BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;

/**
 * Description of ScrapeConfigController
 *
 * @author ndy40
 */
class ScrapeConfigController extends BaseController
{
    /**
     * @var Instance of models\datalogic\AgencyLogic
     */
    protected $agencyRepo;

    /**
     * @var Instance of models\datalogic\ScrapeLogic
     */
    protected $scrapeRepo;
    
    public function __construct() 
    {
        $this->agencyRepo = App::make("AgentLogic");
        $this->scrapeRepo = App::make("ScrapeLogic");
    }
    public function index()
    {
        $data = array();
        if (Cache::has("agency_list")) {
            $data['agents'] = Cache::get("agency_list");
        } else {
            $data['agents'] = $this->agencyRepo->fetchAllAgents();
        }
        
        return View::make("scrape.catalogue", $data);
    }
    
    public function getCatalogue($agencyId)
    {
        $agent = $this->agencyRepo->fetch($agencyId);
        if ($agent) {
            $data = array();
            foreach($agent->catalogues as $catalogue) {
                $data["agent"] = $agent->id;
                $data["urls"][] = array("id" => $catalogue->id, "url" => $catalogue->url);
            }
        }
        return Response::json(array("data" => $data), 200);
    }
    
    public function postInsertCatalogue()
    {
        $data = Input::get("data");
        $id = $data["id"];
        $url = $data["url"];

        $saved = $this->agencyRepo->addCatalogue($id, $url);
        if ($saved) {
            $code = 200;
        } else {
            $code = 400;
        }
        
        return Response::json(array("data" => $saved->toArray()), $code);
    }
    
    public function getDeleteCatalogue($id)
    {
        $deleted = $this->agencyRepo->deleteCatalogue($id);

        if ($deleted) {
            $data = "deleted";
            $code = 200;
        } else {
            $code = 400;
        }
        
        return Response::json($data, $code);
    }

    public function failedScrapes()
    {
        $failedScrapes = $this->scrapeRepo->fetchAllFailedScrapes();

        return View::make("scrape.failed_scrapes", array("scrapes" => $failedScrapes));
    }

    public function postDeleteFailedScrape()
    {
        $id = Input::get("id");
        $sucess = $this->scrapeRepo->deleteFailedScrape($id);
        return Response::json($sucess);
    }
}

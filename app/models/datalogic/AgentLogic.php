<?php
namespace models\datalogic;

use models\interfaces\DataLogicInterface;
use models\entities\Catalogue;
use Illuminate\Support\Facades\App;
use models\repositories\AgentRepository;


/**
 * Description of AgentLogic
 *
 * @author ndy40
 */
class AgentLogic implements DataLogicInterface
{
    protected $agentRepo;
    
    public function __construct()
    {
        $this->agentRepo = new AgentRepository;
    }
    public function fetchAllAgents($column = "name", $direction = "asc")
    {
        return $this->agentRepo->fetchAllAgents($column, $direction);
    }
    
    public function fetch($id) {
        return $this->agentRepo->fetch($id);
    }
    
    public function addCatalogue($agentId, $url) {
        $agent = $this->agentRepo->fetch($agentId);
        $catalogue = new Catalogue();
        $catalogue->url = $url;
        return $agent->catalogues()->save($catalogue);
    }
    
    public function deleteCatalogue($id)
    {
        return $this->agentRepo->deleteCatalogue($id);
    }
    
    public function fetchAgentByNameAndCountry($agent, $country)
    {
        return $this->agentRepo->fetchAgentByNameAndCountry($agent, $country);
    }

    public function fetchCountries()
    {
        return $this->agentRepo->fetchCountries();
    }
}

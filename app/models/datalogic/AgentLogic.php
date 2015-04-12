<<<<<<< HEAD
<?php
namespace models\datalogic;

use models\interfaces\DataLogicInterface;
use models\entities\Catalogue;
use models\repositories\AgentRepository;
use models\interfaces\RepositoryInterface;

/**
 * Description of AgentLogic
 *
 * @author ndy40
 */
class AgentLogic implements DataLogicInterface
{
    protected $agentRepo;
    
    public function __construct(RepositoryInterface $respository)
    {
        $this->agentRepo = $respository;
    }
    public function fetchAllAgents($column = "name", $direction = "asc")
    {
        return $this->agentRepo->fetchAllAgents($column, $direction);
    }
    
    public function fetch($id)
    {
        return $this->agentRepo->fetch($id);
    }
    
    public function addCatalogue($agentId, $url)
    {
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
=======
<?php
namespace models\datalogic;

use models\interfaces\DataLogicInterface;
use models\entities\Catalogue;
use models\repositories\AgentRepository;
use models\interfaces\RepositoryInterface;

/**
 * Description of AgentLogic
 *
 * @author ndy40
 */
class AgentLogic implements DataLogicInterface
{
    protected $agentRepo;
    
    public function __construct(RepositoryInterface $respository)
    {
        $this->agentRepo = $respository;
    }
    public function fetchAllAgents($column = "name", $direction = "asc")
    {
        return $this->agentRepo->fetchAllAgents($column, $direction);
    }
    
    public function fetch($id)
    {
        return $this->agentRepo->fetch($id);
    }
    
    public function addCatalogue($agentId, $url)
    {
        $agent = $this->agentRepo->fetch($agentId);
	if (count(Catalogue::where('url', '=', $url)->get()))
		return;
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
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501

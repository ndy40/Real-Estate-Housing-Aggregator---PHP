<?php

use models\repositories\AgentRepository;

/**
 * Description of AgentRespositoryTest
 *
 * @author ndy40
 */
class AgentRespositoryTest extends TestCase
{
    
    public function testFetchAgent()
    {
        $repo = new AgentRepository;
        $agency = $repo->fetchAllAgents();
        $this->assertInstanceOf("Illuminate\Database\Eloquent\Collection", $agency);
    }
    
}

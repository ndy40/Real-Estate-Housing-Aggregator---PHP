<?php

use models\repositories\AgentRepository;
use models\entities\Country;

/**
 * Description of ScrapeRepositoryTest
 *
 * @author ndy40
 */
class ScrapeRepositoryTest extends TestCase
{
    public function testFetchAgentByNameAndCountry () {
        $scrapeFactory = new AgentRepository;
        
        $country = 'gb';
        $agent = 'zoopla';
        
        $agency = $scrapeFactory->fetchAgentByNameAndCountry($agent, $country);
        $this->assertInstanceOf('\models\entities\Agency', $agency);

        $this->assertInstanceOf('\models\entities\Country', $agency->country);
    }
    
    public function testFailedScrape()
    {
        $scrapeFactory = new \models\repositories\ScrapeRepository;
        $data = array (
            'result' => "<item>Some demo data</item>"
        );
        $failedScraped = $scrapeFactory->saveFailedScrapes('zoopla', 'gb', $data['result']);
        $this->assertInstanceOf('\models\entities\FailedScrapes', $failedScraped);
        $failedScraped->delete();
    }
}

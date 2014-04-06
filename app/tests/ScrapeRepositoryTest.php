<?php

use models\repositories\AgentRepository;
use models\entities\Country;
use models\repositories\ScrapeRepository;

/**
 * Description of ScrapeRepositoryTest
 *
 * @author ndy40
 */
class ScrapeRepositoryTest extends TestCase
{
    protected $scrapeRepo;
    protected $agentRepo;

    public function setUp()
    {
        $this->scrapeRepo = new ScrapeRepository;
        $this->agentRepo  = new AgentRepository;
        
    }
   
    public function testFetchAgentByNameAndCountry()
    {
        $country = 'gb';
        $agent = 'zoopla';
        
        $agency = $this->agentRepo->fetchAgentByNameAndCountry($agent, $country);
        $this->assertInstanceOf('\models\entities\Agency', $agency);

        $this->assertInstanceOf('\models\entities\Country', $agency->country);
    }
    
    public function testFailedScrape()
    {
        $data = array (
            'result' => "<item>Some demo data</item>"
        );
        $failedScraped = $this->scrapeRepo->saveFailedScrapes('zoopla', 'gb', $data['result']);
        $this->assertInstanceOf('\models\entities\FailedScrapes', $failedScraped);
        $failedScraped->delete();
    }
    
    public function testSaveProperty()
    {
        $xml = "<job><country>gb</country><agent>zoopla</agent><scrapetype>item</scrapetype><url>http://www.zoopla.co.uk/for-sale/details/30508207?search_identifier=9b4f572169c31135c7eeed9c5adb8ed0</url><results><item><address>Melior Place, London SE1</address><areacode>SE1</areacode><marketer>Knight Frank - Wapping</marketer><name>3 bedroom semi-detached house for sale</name><phone>020 3641 4747</phone><price>4</price><rooms>3</rooms><status>available</status><type>DETACHED</type></item></results></job>";
        $doc = new DOMDocument;
        $doc->loadXML($xml);
        $property = $this->scrapeRepo->saveProperty($doc);
        $this->assertInstanceOf("\models\entities\Property", $property);
    }
}

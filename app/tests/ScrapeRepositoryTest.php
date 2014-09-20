<?php

use models\repositories\AgentRepository;
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
        parent::setUp();
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
    /**
     * 
     * @dataProvider scrapeProvider
     */
    public function testSaveProperty($xml)
    {
        $doc = new DOMDocument;
        $doc->loadXML($xml);
        $property = $this->scrapeRepo->saveProperty($doc);
        $this->assertTrue($property);
    }
    
    
    public function scrapeProvider()
    {
        return array(
          array("<job><country>gb</country><agent>zoopla</agent><scrapetype>item</scrapetype><url>http://www.zoopla.co.uk/for-sale/details/30508207?search_identifier=9b4f572169c31135c7eeed9c5adb8ed0</url><results><item><address>Melior Places, London AB2</address><areacode>AB2</areacode><marketer>Knight Frank - Wapping</marketer><name>3 bedroom semi-detached house for sale</name><phone>020 3641 4747</phone><price>400000</price><rooms>3</rooms><status>available</status><type>Detached</type><offertype>rent</offertype></item></results></job>"),
        );
    }
}

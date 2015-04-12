<?php
use \DOMDocument;
use models\repositories\PropertyRepository;

/**
 * Description of PropertyRepositoryTest
 *
 * @author ndy40
 */
class PropertyRepositoryTest  extends TestCase
{
    protected $dataModels;
    protected $propertyRepo;

    public function setUp() {
        parent::setUp();
        $dataModels = array();
        $dataModels['testHash'] = array(
            'country' => 'gb',
            'agency'  => 'zoopla',
            'address' => '12 No Mans land',
            'marketer' => 'Zoopla',
            'postcode' => 'CR',
        );
        
    }
    
    /**
     * Test the hash generation logic
     */
    public function testGeneratePropertyHash()
    {
        $propertyRepo = new PropertyRepository;
        $country    = $this->dataModels['testHash']['country'];
        $agency     = $this->dataModels['testHash']['agency'];
        $address    = $this->dataModels['testHash']['address'];
        $marketer   = $this->dataModels['testHash']['marketer'];
        $postcode   = $this->dataModels['testHash']['postcode'];
        
        $hash = $propertyRepo->generatePropertyHash(
            $country, 
            $agency, 
            $address, 
            $marketer, 
            $postcode
        );
        $this->assertTrue(TRUE, $hash);
        $hash2 = $propertyRepo->generatePropertyHash(
            $country, 
            $agency, 
            $address, 
            $marketer, 
            $postcode
        );
        //check if repeated execution generates same result.
        $this->assertSame($hash2, $hash);
        //check if changing one parameter will cause a fail.
        $hash3 = $propertyRepo->generatePropertyHash(
            $country, 
            $agency, 
            $address, 
            $marketer, 
            $postcode
        );
        //check they are the same.
        $this->assertSame($hash3, $hash);
    }
    
}

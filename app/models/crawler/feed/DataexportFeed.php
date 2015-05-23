<?php
namespace models\crawler\feed;

use Illuminate\Filesystem\FileNotFoundException;
use models\crawler\abstracts\FeedAbstract;
use DOMDocument;
use models\crawler\abstracts\JobQueue;
use models\utility\DataExportBLMReader;

/**
 * Description of DataScrape
 *
 * @author kaimin
 */
class DataexportFeed extends FeedAbstract
{

    private $property_type = array(
        'notSupported', 'Terraced', 'End of Terrace', 'Semi-Detached', 'Detached',
        'Mews', 'Cluster House','Ground Flat','Flat','Studio','Ground Maisonette',
        'Maisonette','Bungalow','Terraced Bungalow', 'Semi-Detached Bungalow',
        'Detached Bungalow','Mobile Home',20=>'Land','Link Detached House',
        'Town House','Cottage','Chalet',26=>'Villa',27=>'Villa','Apartment',
        'Penthouse','Finca',43=>'Barn Conversion','Serviced Apartments',
        'Parking','Sheltered Housing','Retirement Property','House Share','Flat Share',
        'Park Home','Garages','Farm House','Equestrian Facility',56=>'Duplex',
        59=>'Triplex',62=>'Longere',65=>'Gite',68=>'Barn', 71=>'Trulli',74=>'Mill',
        77=>'Ruins',80=>'Restaurant',83=>'Cafe',86=>'Mill',92=>'Castle',95=>'Village House',
        101=>'Cave House',104=>'Cortijo',107=>'Farm Land',110=>'Plot',113=>'Country House',
        116=>'Stone House', 'Caravan','Lodge','Log Cabin','Manor House','Stately Home',
        125=>'Off-Plan',128=>'Semi-detached Villa',131=>'Detached Villa',
        134=>'Bar / Nightclub',137=>'Shop',140=>'Riad','House Boat','Hotel Room','Block of Apartments',
        'Private Halls',178=>'Office',181=>'Business Park',184=>'Serviced Office',
        187=>'Retail Property (high street)',190=>'Retail Property (out of town)',
        193=>'Convenience Store',196=>'Garage',199=>'Hairdresser / Barber Shop',
        202=>'Hotel',205=>'Petrol Station',208=>'Post Office',211=>'Pub',
        214=>'Workshop',217=>'Distribution Warehouse',220=>'Factory',223=>'Heavy Industrial',
        226=>'Industrial Park',229=>'Light Industrial',232=>'Storage',
        235=>'Showroom',238=>'Warehouse',241=>'Land',244=>'Commercial Development',
        247=>'Industrial Development',250=>'Residential Development',253=>'Commercial Property',
        256=>'Data Centre',259=>'Farm',262=>'Healthcare Facility',
        265=>'Marine Property',268=>'Mixed Use',271=>'Research & Development Facility',
        274=>'Science Park',277=>'Guest House',280=>'Hospitality',283=>'Leisure Facility',
        298=>'Takeaway',301=>'Childcare Facility',
        304=>'Smallholding',307=>'Place of Worship',310=>'Trade Counter',
        511=>'Coach House'
    );

    public function saveXMLfromBLM($blmpath, $xmlpath, $agentFile, $publish)
    {
        $blm_reader = new DataExportBLMReader($blmpath);
        $blm_arr = $blm_reader->properties();

        $agentData = $this->getAgentDetails($agentFile);
        $properties = array();

        foreach ($blm_arr as $index => $blm) {
            try {
                $property = array();
                $property["marketer"] = $agentData["marketer"];
                $property["phone"]    = $agentData["phone"];

                $address1   = $blm_reader->getData('ADDRESS_1', $index);
                $address2   = $blm_reader->getData('ADDRESS_2', $index);
                $address3   = $blm_reader->getData('ADDRESS_3', $index);

                $town       = $blm_reader->getData('TOWN', $index);
                $county     = $blm_reader->getData('COUNTY', $index);
                $postcode   = $blm_reader->getData('POSTCODE1', $index)
                    . $blm_reader->getData('POSTCODE2', $index);


                $fullAddress = htmlspecialchars($address1 . $address2 . $address3 . $town . $county . ' ' . $postcode);
                $property["address"] = $fullAddress;

                $property["areacode"] = $blm_reader->getData('POSTCODE1', $index);

                $offerType = $blm_reader->getData('TRANS_TYPE_ID', $index);
                $property["offertype"] = (isset($offerType) && $offerType == '1' ) ? 'sale' : 'rent';;

                $property["price"]  = $blm_reader->getData("PRICE", $index);
                $property["rooms"]  = $blm_reader->getData("BEDROOMS", $index);
                $property["status"] = JobQueue::ITEM_AVAILABLE;
                $property['type']   = $this->property_type[$blm_reader->getData('PROP_SUB_ID', $index)];
                $property['url']    = "N/A";
                $property["description"] = htmlspecialchars($blm_reader->getData("DESCRIPTION", $index));
                $property["publish"] = $publish;

                $property["image"] = array();

                for ($i = 0; $i <= 14; $i++) {
                    $img_link = $blm_reader->getData('MEDIA_IMAGE_' . sprintf("%02d", $i), $index);
                    if (empty($img_link)) {
                        continue;
                    }
                    else {
                        $property["image"][] = $img_link;
                    }
                }
                //Add to main property array
                $properties[] = $property;

            } catch (\Exception $ex) {
               continue;
            }
        }

        return $this->buildPropertyXML($properties, $xmlpath);
    }

    public function getAgentDetails($agentFilePath)
    {
        if (!file_exists($agentFilePath)) {
            throw new FileNotFoundException("Can't find agent file for extraction");
        }

        $agentFile = file_get_contents($agentFilePath);
        $agentDoc = new \SimpleXMLElement($agentFile);
        $data = array(
            "marketer" => $agentDoc->agentname->__toString(),
            "phone"    => $agentDoc->telephone->__toString() . ' ' . $agentDoc->email->__toString(),
        );

        return $data;
    }

    public function buildPropertyXML(array $properties, $outputPath)
    {
        $doc = new \SimpleXMLElement('<?xml version="1.0" encoding="ISO-8859-1"?><properties></properties>');

        array_walk($properties, function ($value) use ($doc) {
            $property = $doc->addChild("property");
            foreach ($value as $key => $prop) {
                if (is_array($prop)) {
                    $propertyAttribute = $property->addChild($key . 's'); //make the DOM element plural
                    foreach($prop as $item) {
                        $propertyAttribute->addChild($key, htmlspecialchars($item, ENT_XML1));
                    }
                } else {
                    $property->addChild($key, htmlspecialchars($prop, ENT_XML1));
                }
            }
        });

        return $doc->asXML($outputPath);
    }

}

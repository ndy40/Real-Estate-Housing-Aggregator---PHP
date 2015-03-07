<?php
namespace models\crawler\feed;

use models\crawler\abstracts\FeedAbstract;
use DOMDocument;

/**
 * Description of DataScrape
 *
 * @author kaimin
 */
class DataexportFeed extends FeedAbstract
{
    public function saveXMLfromBLM($blmpath, $xmlpath, $publish)
    {
        $blm_reader = new \BLM\Reader($blmpath);
        $blm_arr = $blm_reader->toArray();

        $doc = new DOMDocument('1.0', 'utf-8');

        $root = $doc->createElement('properties');
        $root = $doc->appendChild($root);

        $property_type = array('notSupported','Terraced','End of Terrace','Semi-Detached','Detached','Mews','Cluster House','Ground Flat','Flat','Studio','Ground Maisonette','Maisonette','Bungalow','Terraced Bungalow',
            'Semi-Detached Bungalow','Detached Bungalow','Mobile Home',20=>'Land','Link Detached House','Town House','Cottage','Chalet',26=>'Villa',27=>'Villa','Apartment','Penthouse','Finca',43=>'Barn Conversion','Serviced Apartments',
            'Parking','Sheltered Housing','Retirement Property','House Share','Flat Share','Park Home','Garages','Farm House','Equestrian Facility',56=>'Duplex',59=>'Triplex',62=>'Longere',65=>'Gite',68=>'Barn',
            71=>'Trulli',74=>'Mill',77=>'Ruins',80=>'Restaurant',83=>'Cafe',86=>'Mill',92=>'Castle',95=>'Village House',101=>'Cave House',104=>'Cortijo',107=>'Farm Land',110=>'Plot',113=>'Country House',116=>'Stone House',
            'Caravan','Lodge','Log Cabin','Manor House','Stately Home',125=>'Off-Plan',128=>'Semi-detached Villa',131=>'Detached Villa',134=>'Bar / Nightclub',137=>'Shop',140=>'Riad','House Boat','Hotel Room','Block of Apartments',
            'Private Halls',178=>'Office',181=>'Business Park',184=>'Serviced Office',187=>'Retail Property (high street)',190=>'Retail Property (out of town)',193=>'Convenience Store',196=>'Garage',199=>'Hairdresser / Barber Shop',
            202=>'Hotel',205=>'Petrol Station',208=>'Post Office',211=>'Pub',214=>'Workshop',217=>'Distribution Warehouse',220=>'Factory',223=>'Heavy Industrial',226=>'Industrial Park',229=>'Light Industrial',232=>'Storage',
            235=>'Showroom',238=>'Warehouse',241=>'Land',244=>'Commercial Development',247=>'Industrial Development',250=>'Residential Development',253=>'Commercial Property',256=>'Data Centre',259=>'Farm',262=>'Healthcare Facility',
            265=>'Marine Property',268=>'Mixed Use',271=>'Research & Development Facility',274=>'Science Park',277=>'Guest House',280=>'Hospitality',283=>'Leisure Facility',298=>'Takeaway',301=>'Childcare Facility',
            304=>'Smallholding',307=>'Place of Worship',310=>'Trade Counter',511=>'Coach House');


        foreach ($blm_arr as $blm) {
            $property = $doc->createElement('property');
            $property = $root->appendChild($property);

            $address1 = $blm['ADDRESS_1']!=''?htmlspecialchars($blm['ADDRESS_1']).',':'';
            $address2 = $blm['ADDRESS_2']!=''?htmlspecialchars($blm['ADDRESS_2']).',':'';
            $address3 = $blm['ADDRESS_3']!=''?htmlspecialchars($blm['ADDRESS_3']).',':'';
            $town = $blm['TOWN']!=''?htmlspecialchars($blm['TOWN']).',':'';
            $county = $blm['COUNTY']!=''?htmlspecialchars($blm['COUNTY']):'';
            $address = $doc->createElement('address', $address1.$address2.$address3.$town.$county.' '.$blm['POSTCODE1']);
            $areacode = $doc->createElement('areacode', $blm['POSTCODE1'].' '.$blm['POSTCODE2']);
            $offertype = $doc->createElement('offertype', ($blm['TRANS_TYPE_ID']=='1')?'sale':'rent');
            $price = $doc->createElement('price', $blm['PRICE']);
            $rooms = $doc->createElement('rooms', $blm['BEDROOMS']);
            $status = $doc->createElement('status', $blm['AGENT_REF']);
            $type = $doc->createElement('type', $property_type[$blm['PROP_SUB_ID']]);
            $url = $doc->createElement('url', '');
            $description = $doc->createElement('description', htmlspecialchars($blm['DESCRIPTION']));
			$publish_tag = $doc->createElement('publish', $publish);
            $images = $doc->createElement('images');
            $image = array();
            for ($i=0; $i<=14; $i++) {
                $img_link = $blm['MEDIA_IMAGE_' . sprintf("%02d", $i)];
                if ($img_link == '')
                    break;
                $image[$i] = $doc->createElement('image', $img_link);
            }

            $property->appendChild($address);
            $property->appendChild($areacode);
            $property->appendChild($offertype);
            $property->appendChild($price);
            $property->appendChild($rooms);
            $property->appendChild($status);
            $property->appendChild($type);
            $property->appendChild($url);
            $property->appendChild($description);
			$property->appendChild($publish_tag);
            $images = $property->appendChild($images);
            for ($i=0; $i<count($image); $i++)
                $images->appendChild($image[$i]);
            $property->appendChild($images);
        }


        if ($doc->save($xmlpath) != false)
            return true;
        return false;
    }
}

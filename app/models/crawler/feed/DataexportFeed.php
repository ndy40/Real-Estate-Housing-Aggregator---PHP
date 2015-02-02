<?php
namespace models\crawler\feed;

use models\crawler\abstracts\FeedAbstract;

/**
 * Description of DataScrape
 *
 * @author kaimin
 */
class DataexportFeed extends FeedAbstract
{
    public function saveXMLfromBLM($blmpath, $xmlpath)
    {
        $blm_reader = new \BLM\Reader($blmpath);
        $blm_arr = $blm_reader->toArray();

        $doc = new \DOMDocument();

        $root = $doc->createElement('properties');
        $root = $doc->appendChild($root);

        foreach ($blm_arr as $blm) {
            $property = $doc->createElement('property');
            $property = $root->appendChild($property);

            $address = $doc->createElement('address', $blm['DISPLAY_ADDRESS'].','.$blm['TOWN'].','.$blm['COUNTY'].' '.$blm['POSTCODE1']);
            $areacode = $doc->createElement('areacode', $blm['POSTCODE1']);
            $offertype = $doc->createElement('offertype', ($blm['TRANS_TYPE_ID']=='1')?'sale':'rent');
            $price = $doc->createElement('price', $blm['PRICE']);
            $rooms = $doc->createElement('rooms', $blm['BEDROOMS']);
            $status = $doc->createElement('status', 'available');
            $type = $doc->createElement('type', 'House');
            $url = $doc->createElement('url', '');
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

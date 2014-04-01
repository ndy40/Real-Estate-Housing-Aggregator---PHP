<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\repositories;

use models\interfaces\RepositoryInterface;
use models\entities\PostCode;
use Illuminate\Support\Facades\Crypt;
use models\entities\Property;

/**
 * Description of PropertyRepository
 *
 * @author ndy40
 */
class PropertyRepository implements RepositoryInterface
{
    public function delete($id) {
        
    }

    public function fetch($id) {
        
    }

    public function update($entity) {
        
    }
    
    public function fetchPropertyByHash($hash) {
        return Property::where('hash', '=', $hash)->first();
    }

    public function fetchPostCode ($postcode)
    {
        $postCode = null;
        if (is_numeric($postCode)) {
            $postCode = PostCode::find($postCode);
        } else {
            $postCode = PostCode::where('code', '=', strtoupper($postCode))
                ->first();
        }
        return $postCode;
    }
    
    public function generatePropertyHash(
        $country, 
        $agent, 
        $address, 
        $marketer, 
        $postcode,
        $address
    ){
        $hashString = $country . $agent . $address . $marketer 
            . $postcode . $address;
        return Crypt::encrypt($hashString);
    }
}

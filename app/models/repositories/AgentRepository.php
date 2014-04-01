<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models\repositories;

use models\interfaces\RepositoryInterface;
use models\entities\Agency;
use models\entities\Country;

/**
 * Description of AgentRepository
 *
 * @author ndy40
 */
class AgentRepository  implements RepositoryInterface
{
    public function delete($id) {
        Agency::destroy($ids);
    }

    public function fetch($id) {
        return Agency::find($id);
    }

    public function update($id, $attributes = array()) {
        $entity = Agency::find($id);
        $entity->assignAttributes($attributes);
        $entity->save();
    }
    
    public function fetchAgentByNameAndCountry($agency, $country) {
        return Country::where('code', '=', $country)->first()
            ->agencies()->where('crawler', '=', $agency)->first();
    }

}

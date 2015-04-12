<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GroupTableSeeder
 *
 * @author ndy40
 */
class GroupTableSeeder extends Seeder
{
    public function run()
    {
        //create super user
        Sentry::createGroup(array(
            'name' => 'super_user',
            'permissions' => array(
                'superuser' => 1
            )
        ));
        
        Sentry::createGroup(array(
            'name' => 'user'
        ));
        
        
    }
}

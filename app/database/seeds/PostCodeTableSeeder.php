<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PostCodeTableSeeder
 *
 * @author ndy40
 */
class PostCodeTableSeeder extends Seeder 
{
    public function run()
    {
        $this->command->info('Starting postcode seed.');
        $filePath = storage_path() . '/data/postcode.csv';
        $data = $this->readFile($filePath);
        foreach ($data as $value) {
            foreach($value['names'] as $name) {
                $countyId = $this->getCountyId($value['county']);
                if ($countyId === null) {
                    throw new Exception ("Can't find county " . $value['county']);
                }
                $postcode = $value['code'];
                $this->insertPostCode($countyId, $postcode, $name);
            }
        }
        
        $this->command->info('Finished post code seed.');
    }
    
    protected function readFile($filename)
    {
        if (!is_file($filename)) {
            throw new Exception ("File not found " . $filename);
        }
        
        $file = fopen($filename, 'r');
        $postCodes = array();
        $count = -1;
        while (($data = fgetcsv($file)) !== false) {
            ++$count;
            $postCodes[$count]['code'] = $data[0];
            $postCodes[$count]['names'] = explode(',', $data[1]);
            $postCodes[$count]['county'] = $data[2];
        }
        fclose($file);
        return $postCodes;
    }
    
    protected function getCountyId($name)
    {
        return DB::table('county')->where('name', '=', ucwords($name))
            ->pluck('id');
    }
    
    protected function insertPostCode($countyId, $postcode, $area) {
        DB::table('post_codes')->insert(array(
            array(
                'code' => strtoupper(trim($postcode)),
                'area' => ucwords(strtolower(trim($area))),
                'county_id' => $countyId
            ),
        ));
    }
}

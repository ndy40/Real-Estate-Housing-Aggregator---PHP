<?php

/**
 * Description of PropertyTableSeeder
 *
 * @author ndy40
 */
class PropertyTableSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('running county table seeding.');
        $countryId = DB::table('country')->where('code', '=', 'gb')
            ->pluck('id');
        $dataFile = storage_path() . '/data/county.csv';
        $countyNames = $this->readDataFile($dataFile);
        foreach($countyNames as $name => $value) {
            DB::table('county')->insert(array(
                array('name' => trim($value), 'country_id' => $countryId),
            ));
        }
        $this->command->info('Finished county table seeding.');
        
    }
    
    protected function readDataFile($filename){
        if (!is_file($filename)) {
            throw new Exception("File not cound. File: " . $filename );
        }
        
        $file = fopen($filename, 'r');
        $countyNames = array();
        
        while(($data = fgetcsv($file)) !== false){
            $countyNames[] = $data[0];
        }
        
        return $countyNames;
    }
}

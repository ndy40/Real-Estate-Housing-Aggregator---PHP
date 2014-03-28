<?php
namespace models\crawler\factories;

/**
 * Description of ScrapeFactory
 *
 * @author ndy40
 */
class ScrapeFactory {
    

    public function getScrape ($type, $config = array()) {
        if (empty($type)) {
            throw new Exception("Invalid scrape type supplied");
        }
        
        $scrape = "models\\crawler\\scrape\\" . ucfirst($type) . "Scrape";
       
        return new $scrape($config);
    }
    
    public function getName () {
        return "Name";
    }
}

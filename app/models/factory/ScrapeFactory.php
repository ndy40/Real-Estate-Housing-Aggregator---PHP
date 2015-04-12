<?php
namespace models\factory;

/**
 * Description of ScrapeFactory
 *
 * @author Ndifreke Ekott <ndy40.ekott@gmail.com>
 */
class ScrapeFactory implements \models\interfaces\FactoryInterface
{
    /**
     * This is a convenient method for generating and configurating a scrape type.
     * 
     * @param string $type The type of scrape values are list or item.
     * @param array $config
     * @return \models\crawler\scrape\ListScrape|\models\crawler\scrape\ItemScrape|\models\crawler\scrape\ListScrape|\models\crawler\scrape\DataScrape
     * @throws Exception
     */
    public function getScrape ($type, array $config = array())
    {
        if (empty($type)) {
            throw new Exception("Invalid scrape type supplied");
        }

        $scrape = "models\\crawler\\scrape\\" . ucfirst($type) . "Scrape";

        return new $scrape($config);
    }

    public function getName()
    {
        return "Name";
    }
}

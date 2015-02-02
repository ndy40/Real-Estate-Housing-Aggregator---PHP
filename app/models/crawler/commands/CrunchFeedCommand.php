<?php
namespace crunch;

use Illuminate\Console\Command;
use models\interfaces\FactoryInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;
use DOMDocument;
use models\crawler\feed\DataexportFeed;

class CrunchFeedCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crunch:feed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape products from a provided dataexport url.';

    /**
     * An instance of the scrape factory.
     *
     * @var \models\factory\ScrapeFactory
     */
    private $scrapeFactory;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FactoryInterface $scrapeFactory)
    {
        parent::__construct();
        $this->scrapeFactory = $scrapeFactory;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info("Configuring catalogue scrape.");

        $config = $this->argument();
        $config['type']       = 'type';
        $config['debug']      = $this->option('debug');
        $config["proxy"]      = $this->option("proxy");
        $config['command']    = Config::get('crawler.command');
        $config['scriptName'] = Config::get('crawler.script_name');
        $config['configFile'] = Config::get('crawler.config_file');

        $this->info("Unzipping property detail files.");

        $upload_dir = Config::get('crawler.upload_data_path');
        $recent_filename = $this->getRecentFileName($upload_dir, $config['url']);

        $feed = new \models\crawler\feed\DataexportFeed();
        $feed->saveXMLfromBLM($upload_dir.'/extract/'.$recent_filename.'.BLM', $upload_dir.'/xmlfromblm/'.$config['url'].'.xml');

        $config['url'] = $upload_dir.'/xmlfromblm/'.$config['url'].'.xml';

//        $config['url'] = $this->saveXMLfromBLM($config['url']);

        $this->info("Extracted files into ".Config::get('crawler.upload_data_path').'/xmlfromblm/');

        $this->info("Initializing scrape factory.");

        $scrape = null;
		
		$scrape = $this->scrapeFactory->getScrape('feed', $config);

        $this->info("Initialization complete.");
        $this->info("Executing scrape.");

        $output = $scrape->execute();

        $this->info("Scrape complete");

        //generate xml document and start working
        $xmlDoc = new \DOMDocument();
        $xmlDoc->loadXML($output);

        //this is where you assign the schema and validate data.
        // TODO: Add xml schema and valiate record then handle error.

        //write output to file.
        $filePath = Config::get('crawler.output_path');
        $filePath .= '/' . $this->argument('country') .'-'
            . $this->argument('agent') . date('dmYHis') . '.xml';
        $file = fopen($filePath, 'w');
        fwrite($file, $output);
        fclose($file);
        chmod($filePath, 0666);

        $this->info("Generating output file. " . $file);

        $this->info("Pushing to queue.");
		
        $data = array (
            'country' => $this->argument('country'),
            'agent'  => $this->argument('agent'),
            'url'     => $this->argument('url'),
            'result'  => $filePath,
        );

        $this->info($this->name . " before push.");
		Queue::push('DataQueue', $data);

        $this->info($this->name . " completed.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('country', InputArgument::REQUIRED, 'Country code'),
            array('agent', InputArgument::REQUIRED, 'Agent Name'),
            array('url', InputArgument::REQUIRED, 'Url to crawl'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('debug', null, InputOption::VALUE_NONE, 'Set scrape mode to debug'),
            array('proxy', null, InputOption::VALUE_OPTIONAL, 'Set scrape mode to debug', Config::get("crawler.tor_port")),
        );
    }

    private function saveXMLfromBLM($blmname) {
        $upload_dir = Config::get('crawler.upload_data_path');
        $recent_file = $this->getRecentFileName($upload_dir, $blmname);

        $zipper = new \Chumper\Zipper\Zipper;;
        $zipper->make($upload_dir.'/'.$recent_file.'.zip')->folder('')->extractTo($upload_dir.'/extract');

        $blm_reader = new \BLM\Reader($upload_dir.'/extract/'.$recent_file.'.BLM');
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
        $savedir = $upload_dir.'/xmlfromblm/'.$blmname.'.xml';
        $doc->save($savedir);
        return $savedir;
    }

    private function getRecentFileName($upload_dir, $blmname) {
        $dir = opendir($upload_dir);
        $files = array();
        while(false != ($file = readdir($dir))) {
            if (strpos($file, $blmname) !== false and strpos($file, '.zip') !== false)
                $files[] = $file;
        }
        asort($files);
        return str_replace('.zip', '', end($files));
    }
}

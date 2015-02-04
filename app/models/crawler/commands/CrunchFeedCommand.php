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
use Illuminate\Support\Facades\Artisan;

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

        //extract recent blm zip file.
        $zipper = new \Chumper\Zipper\Zipper;;
        $zipfile = $upload_dir.'/'.$recent_filename.'.zip';
        $zipper->make($zipfile)->folder('')->extractTo($upload_dir.'/extract');

        //save xml file from unzipped blm file.
        $feed = new \models\crawler\feed\DataexportFeed();
        $blmfile = $upload_dir.'/extract/'.$recent_filename.'.BLM';
        $xmlfile = $upload_dir.'/xmlfromblm/'.$config['url'].'.xml';
        $feed->saveXMLfromBLM($blmfile, $xmlfile);

        $config['url'] = $xmlfile;

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
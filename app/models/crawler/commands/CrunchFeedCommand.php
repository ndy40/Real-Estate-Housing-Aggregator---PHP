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

        if ($config['publish'] === 1)
        	$upload_dir = Config::get('crawler.dataexport_upload_path');
		else
			$upload_dir = Config::get('crawler.dataexport_upload_path') . '/unpublish';
        $extract_dir = storage_path() . "/data/dataexport/uploads";
        $recent_filename = $this->getRecentFileName($upload_dir, $config['url']);
		if ($recent_filename === 'No file')
			return;

		$agent_filename = explode('_', $recent_filename);
		$agent_filename = $agent_filename[0].'_AGENT.XML';
		if (file_exists(Config::get('crawler.dataexport_upload_path').'/XML/'.$agent_filename) !== TRUE)
			return;
		if (filesize(Config::get('crawler.dataexport_upload_path').'/XML/'.$agent_filename) == 0)
			return;

        //extract recent blm zip file.
        $zip = new \ZipArchive;
        $zipfile = $upload_dir.'/'.$recent_filename.'.zip';
        if ($zip->open($zipfile) == TRUE || filesize($zipfile) == 0) {
            $zip->extractTo($extract_dir.'/extract');
            $zip->close();
        } else {
            //echo 'failed extract';
            return;
        }

        //save xml file from unzipped blm file.
        $feed = new \models\crawler\feed\DataexportFeed();
        $blmfile = $extract_dir.'/extract/'.$recent_filename.'.BLM';
        $xmlfile = $extract_dir.'/xmlfromblm/'.$config['url'].'.xml';
        $feed->saveXMLfromBLM($blmfile, $xmlfile, $config['publish']);

        $config['url'] = $xmlfile.','.Config::get('crawler.dataexport_upload_path').','.$config['url'];

        $this->info("Extracted files into ".$extract_dir.'/xmlfromblm/');

        $this->info("Initializing scrape factory.");

        $scrape = null;
		
		$scrape = $this->scrapeFactory->getScrape('feed', $config);

        $this->info("Initialization complete.");
        $this->info("Executing scrape.");

        $output = $scrape->execute();

        $this->info("Scrape complete");

        //this is where you assign the schema and validate data.
        // TODO: Add xml schema and valiate record then handle error.

        //write output to file.
        $filePath = Config::get('crawler.output_path');
        $microtime = microtime(true);
        $microtime = sprintf("%06d",($microtime - floor($microtime)) * 1000000);
        $filePath .= '/' . $this->argument('country') .'-'
            . $this->argument('agent') . date('dmYHisu').$microtime . '.xml';
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
	
	//remove xml and blm files created from blm zip
        unlink($xmlfile);
	    unlink($blmfile);
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
            array('publish', InputArgument::REQUIRED, 'Publish'),
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
		if (empty($files))
			return 'No file';
        asort($files);

        $zip = new \ZipArchive;
        $zipfile = $upload_dir.'/'.end($files);
        if ($zip->open($zipfile) != TRUE || filesize($zipfile) == 0)   //don't upload completely yet
            array_pop($files);

        return str_replace('.zip', '', end($files));
    }
}

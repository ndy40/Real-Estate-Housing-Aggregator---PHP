<?php 
namespace crunch;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;


class CrunchListCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'crunch:list';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Scrape a list of products from a provided url.';
    
    /**
     * An instance of the scrape factory.
     * 
     * @var \models\crawler\ScrapeFactory
     */
    private $scrapeFactory;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
        $this->scrapeFactory = App::make('ScrapeFactory');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $config = $this->argument();
        $config['type']       = 'type';
        $config['debug']      = $this->option('debug');
        $config['command']    = Config::get('crawler.command');
        $config['scriptName'] = Config::get('crawler.script_name');
        $config['configFile'] = Config::get('crawler.config_file');
        
        $scrape = $this->scrapeFactory->getScrape('list', $config);
        $output = $scrape->execute();
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
        
           $data = array (
            'country' => $this->argument('country'),
            'agency'  => $this->argument('agent'),
            'url'     => $this->argument('url'),
            'result'  => $filePath,
        );
        
        Queue::push('JobQueue@fetchDetails', $data);
        
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
		);
	}

}
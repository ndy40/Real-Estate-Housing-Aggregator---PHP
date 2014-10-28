<?php
namespace crunch;

use Illuminate\Console\Command;
use models\interfaces\FactoryInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;

class CrunchListCommand extends Command
{
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

        $this->info("Initializing scrape factory.");

        $scrape = $this->scrapeFactory->getScrape('list', $config);

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

        $data = array (
            'country' => $this->argument('country'),
            'agent'  => $this->argument('agent'),
            'url'     => $this->argument('url'),
            'result'  => $filePath,
        );
        $this->info("Pushing to queue.");

        Queue::push('ListingQueue', $data);

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
}

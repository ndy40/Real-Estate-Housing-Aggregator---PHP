<?php 
namespace crunch;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;

class CrunchItemCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crunch:item';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';
    
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
        $this->info("Building scrape");
        
        $config = $this->argument();
        $config['type'] = 'item';
        $config['debug'] = $this->option('debug');
        $config['command'] = Config::get('crawler.command');
        $config["proxy"]      = $this->option("proxy");
        $config['scriptName'] = Config::get('crawler.script_name');
        $config['configFile'] = Config::get('crawler.config_file');
        
        $this->info("Generating factory");
        $scrape = $this->scrapeFactory->getScrape('item', $config);
        
        $this->info('Scrape started');
        
        $output = $scrape->execute();

        $data = array (
            'country' => $this->argument('country'),
            'agency'  => $this->argument('agent'),
            'url'     => $this->argument('url'),
            'result'  => $output,
        );
        
        Queue::push('JobQueue@itemDetails', $data, "properties");
        $this->info("Scrape finished..");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('country', InputArgument::REQUIRED, 'Enter country'),
            array('agent',   InputArgument::REQUIRED, 'Enter agent'),
            array('url',     InputArgument::REQUIRED, 'Enter property url'),
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
            array('debug', null, InputOption::VALUE_NONE, 'Enable debug mode'),
            array('proxy', null, InputOption::VALUE_OPTIONAL, 'Enable proxy mode', "127.0.0.1:9050"),
        );
    }
}

<?php
namespace crunch;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use models\interfaces\DataLogicInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CrunchScrapeCommand extends Command
{

    /**
         * The console command name.
         *
         * @var string
         */
    protected $name = 'crunch:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run full scrape';

    protected $agentLogic;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DataLogicInterface $agentLogic)
    {
        parent::__construct();
        $this->agentLogic = $agentLogic;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $country = $this->argument("country");
        $agent   = $this->argument("agent");
        $debug   = $this->option("debug");

        $agency  = $this->agentLogic->fetchAgentByNameAndCountry(
            $agent,
            $country
        );
        $catalogues = $agency->catalogues;
        $count = 0;
		
		if ($agent == "dataexport")
			$artisan_command = "crunch:feed";
		else if ($agent == "zoopla")
			$artisan_command = "crunch:list";
		
        foreach ($catalogues as $catalogue) {
            $this->info("Scraping Url " . $catalogue->url);
            $scrapeData = array(
               "country" => $country,
                "agent"  => $agent,
                "url"    => $catalogue->url,
            );

            if ($debug) {
                $scrapeData[] = "--debug";
            }

            if ($this->option("proxy")) {
                $scrapeData["--proxy"] = $this->option("proxy");
            }

            $this->call($artisan_command, $scrapeData);
            $this->info("Finished scraping " . $catalogue->url);
        }

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('country', InputArgument::REQUIRED, 'Country to scrape.'),
            array('agent',   InputArgument::REQUIRED, 'Agent to be ran.'),
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
            array('proxy', null, InputOption::VALUE_OPTIONAL, 'Enable proxy mode', Config::get("crawler.tor_port")),
        );
    }
}

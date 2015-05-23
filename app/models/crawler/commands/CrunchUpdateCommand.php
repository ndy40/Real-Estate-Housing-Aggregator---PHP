<?php
namespace crunch;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Indatus\Dispatcher\Scheduler;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Scheduling\ScheduledCommandInterface;
use models\entities\Catalogue;
use models\entities\Property;
use models\interfaces\DataLogicInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CrunchUpdateCommand extends Command implements ScheduledCommandInterface
{

    /**
         * The console command name.
         *
         * @var string
         */
    protected $name = 'crunch:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run update scrape periodically.';

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

		if ($agent == "dataexport")
			$artisan_command = "crunch:feed";
		else if ($agent == "zoopla")
			$artisan_command = "crunch:list";

		$publish = 1;

		$files = glob(Config::get('crawler.dataexport_upload_path') . '/*');
		$unpublish = glob(Config::get('crawler.dataexport_upload_path') . '/unpublish/*');
		$files[] = 'unpublish';
		$files = array_merge($files, $unpublish);

		foreach ($files as $file) {
			if ($file === 'unpublish') {
				$publish = 0;
				continue;
			}
			if (strpos($file, '.zip') === false){
				continue;
			}
            $tmp = explode('_', basename($file));
            $name = $tmp[0];
			$this->agentLogic->addCatalogue($agency->id, $name);
			$this->info("Scraping Url " . $name);
            $scrapeData = array(
               "country" => $country,
                "agent"  => $agent,
                "url"    => $name,
                "publish" => $publish,
            );

            if ($debug) {
                $scrapeData[] = "--debug";
            }

            if ($this->option("proxy")) {
                $scrapeData["--proxy"] = $this->option("proxy");
            }

            $this->call($artisan_command, $scrapeData);
            try {
                unlink(Config::get('crawler.dataexport_upload_path') . '/XML/' . $name . '_AGENT.XML');
                unlink($file);
            } catch (\ErrorException $ex) {
                Log::error($ex->getMessage());
            }

            $this->info("Finished scraping " . $name);
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

    /**
     * User to run the command as
     * @return string Defaults to false to run as default user
     */
    public function user()
    {
        return "ftpcrunch";
    }

    /**
     * When a command should run
     * @param Scheduler $scheduler
     * @return \Indatus\Dispatcher\Scheduling\Schedulable|\Indatus\Dispatcher\Scheduling\Schedulable[]
     */
    public function schedule(Schedulable $scheduler)
    {
        return $scheduler->daily()->everyHours(3)->args(["gb", "dataexport"]);
    }

    /**
     * Environment(s) under which the given command should run
     * Defaults to '*' for all environments
     * @return string|array
     */
    public function environment()
    {
        return "production";
    }


}

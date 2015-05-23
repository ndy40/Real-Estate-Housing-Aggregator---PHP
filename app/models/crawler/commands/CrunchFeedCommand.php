<?php
namespace crunch;

use BLM\Reader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Indatus\Dispatcher\Scheduler;
use models\crawler\feed\DataexportFeed;
use models\factory\ScrapeFactory;
use models\interfaces\FactoryInterface;
use models\repositories\FeedRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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
     * @var ScrapeFactory
     */
    private $scrapeFactory;

    private $dataFeedExport;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FactoryInterface $scrapeFactory, FeedRepository $repo)
    {
        parent::__construct();
        $this->scrapeFactory = $scrapeFactory;
        $this->dataFeedExport = new DataexportFeed;
        $this->feedRepository = $repo;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info("Configuring catalogue scrape.");

        $config = array_merge($this->argument(), $this->option());
        $config['type'] = 'type';

        $config['command']    = Config::get('crawler.command');
        $config['scriptName'] = Config::get('crawler.script_name');
        $config['configFile'] = Config::get('crawler.config_file');

        $this->info("Unzipping property detail files.");

        $upload_dir = ($config['published'])
            ? Config::get('crawler.dataexport_upload_path')
            : Config::get('crawler.dataexport_unpublished');

        $recentFilenames = $this->getRecentFileName($upload_dir, $config['url']);

		if ($recentFilenames === 'No file')
			return;

        foreach ($recentFilenames as $files) {
            $agent_filename = explode('_', $files);
            $agentXML = Config::get('crawler.dataexport_upload_path') . '/XML/' . $agent_filename[0].'_AGENT.XML';
            $agentFileId = $agent_filename[0];
            $blmFileName = substr($files, 0, strrpos($files, ".zip"));

            $agentFileExists = $this->getAgentXML($agentXML);

            if (!$agentFileExists) {
                Log::warning("Can't find agent file for {$agentXML} for property file {$files}");
                continue;
            }

            $this->extractZipFile("$upload_dir/{$files}", Config::get('crawler.dataexport_extract_path'));

            $timestamp = microtime(true);
            $timestamp = sprintf("%06d",($timestamp - floor($timestamp)) * 1000000);

            $blmfile = Config::get('crawler.dataexport_extract_path') . $blmFileName . '.BLM';
            $xmlfile = Config::get('crawler.dataexport_xml_path')     . $blmFileName . $timestamp . '.xml';

            $dataSaved = $this->dataFeedExport->saveXMLfromBLM($blmfile, $xmlfile, $agentXML, $config['published']);

            if ($dataSaved) {
                chmod($xmlfile, 0666);
                $data = array (
                    'country' => $this->argument('country'),
                    'agent'  => $this->argument('agent'),
                    'result'  => $xmlfile,
                );
                Queue::push('DetailsQueue', $data);
                unlink($blmfile);
                unlink("$upload_dir/{$files}");
            }

            $this->info("Scrape complete");
        }

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
            array('published', null, InputOption::VALUE_NONE, 'fetch published properties'),
        );
    }

    private function getRecentFileName($upload_dir)
    {
        $files = array();
        $contents = scandir($upload_dir);

        foreach($contents as $item) {
            if (is_file($upload_dir . "/{$item}")
                && strpos($item, '.zip')
            )
                $files[] = $item;
        };

        return $files;
    }

    public function saveProcessedFiles($export_id, $xml_contents, $published)
    {

    }

    public function getAgentXML($agentFileName)
    {
        return (file_exists($agentFileName) && (filesize($agentFileName) > 0));
    }


    public function extractZipFile($inFile, $outputDirectory)
    {
        try {
            $command = "unzip -o {$inFile} -d {$outputDirectory} > /dev/null";
            if (is_file($inFile)) {
                system($command);
            }
//            unlink($inFile);
        } catch (\Exception $ex) {
            return false;
        }

        return true;
    }

}

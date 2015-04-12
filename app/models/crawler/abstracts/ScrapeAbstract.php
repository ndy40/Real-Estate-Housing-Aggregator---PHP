<?php
/**
 * This is an Abstract class all Scrape related classes will extend.
 */
namespace models\crawler\abstracts;

use models\exceptions\ScrapeInitializationException;

/**
 * Description of Scrape
 *
 * @author Ndifreke Ekott <ndy40.ekott@gmail.com>
 * @package models\crawler\abstracts
 * 
 */
abstract class ScrapeAbstract
{
    /**
     * The scrape type being executed.
     * 
     * @var string 
     */
    protected $type;

    /**
     * The country code of the Country under scrape.
     * 
     * @var string 
     */
    protected $country;
    
    /**
     * The name of the Agent to call for this scrape. Name should match name of
     * the JavaScript file used during the scrape.
     * 
     * @var string 
     */
    protected $agent;
    
    /**
     * The termnal command to be parsed the shell to invoke the scrape.
     * 
     * @var string
     */
    protected $command;

    /**
     * The name of the script file execute.
     * 
     * @var string
     */
    protected $scriptName;
    
    /**
     * The debug flag. If set to true will display verbose scrape messages.
     * 
     * @var boolean
     */
    protected $debug = false;
    
    /**
     * The URL to scrape.
     * 
     * @var string 
     */
    protected $url;
    
    /**
     * The output of the scrape command.
     * 
     * @var string[]
     */
    protected $output;
    
    /**
     * The log level of the scrape engine. Default is error. 
     * @var string 
     */
    protected $logLevel = 'error';

    protected $startTime;

    protected $completionTime;

    protected $configFile;

    protected $proxy;

    protected $proxyType = "socks5";
    
    protected $scrapeCommand;

    /**
     * @return string
     */
    public function getAgent()
    {
        return $this->agent;
    }


    public function __construct($config = array ())
    {
        if (!is_array($config) || empty($config)) {
            throw new ScrapeInitializationException(
                "Empty/Invalid scrape config parameter passed"
            );
        }
        unset($config['startTime']);
        unset($config['completionTime']);

        $this->setConfiguration($config);
        $this->initialize();
    }

    public function set($name, $value)
    {
        $this->{$name} = $value;
    }

    public function get($name)
    {
        return $this->{$name};
    }

    /**
     * Utility method to mass assign configuration parameters.
     *
     * @param array $config Associative array of configuration parameters.
     */
    public function setConfiguration ($config)
    {
        //initialize all parameters
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
    }
    /**
     * The initialization method. Overrite to prepare for scrape execution.
     */
    protected function initialize ()
    {
        $this->startTime = time();
        $this->scrapeCommand = $this->buildCommand(
            $this->command,
            $this->scriptName,
            $this->country,
            $this->agent,
            $this->url,
            $this->type,
            $this->logLevel,
            $this->debug,
            $this->configFile,
            $this->proxy
        );
    }

    public function execute ()
    {
        $output = shell_exec($this->scrapeCommand);

        $this->checkForErrors($output);

        $this->output = $this->handleResult($output);

        return trim($this->output);

    }

    /**
     * A simple method used to build the command string to be executed
     * @return string
     */
    protected function buildCommand (
        $command,
        $scriptName,
        $country,
        $agent,
        $url,
        $type,
        $logLevel = 'error',
        $debug = false,
        $configFile = 'config.json',
        $proxy = '',
        $proxyType = 'socks5'
    ) {
        $commandOutput = '';

        $commandOutput .= $command. " '" . $scriptName . "' "
            . $country . " " . $agent . " '" . $url
            . "' ". $type . " --log-level=" . $logLevel . " "
            . "--config=" . "'$configFile'";

        if ($debug) {
            $commandOutput .= " --verbose=true";
        }

        if ($proxy !== '') {
            $commandOutput .= " --proxy=" . $proxy;
            $commandOutput .= " --proxy-type=" . $proxyType;
        }

        return $commandOutput;
    }
    /**
     * Method that is called to handle results. This will be unique to each subclass.
     *
     * @param string $result The string (XML) representation of result.
     * @return DOMNode If the result was properly handled true is returned.
     */
    abstract protected function handleResult($result);

    /**
     * Check for error in returned data.
     *
     * @param string $result string representing the output of a scrape.
     */
    abstract protected function checkForErrors($result);

    /**
     * Queue results of scrape for further processing.
     *
     * @param JobQueue $jobQueue
     * @param string $result
     */
    protected function queueResult($jobQueue, $result)
    {
        throw new Exception("Not implemented yet.");
    }

    /**
     * Clean up task when done. E.g Close connection and null objects for GC
     */
    protected function cleanUp()
    {
        throw new Exception("Not implemnted yet");
    }
    
    public function getScrapeCommand () {
        return $this->scrapeCommand;
    }
}

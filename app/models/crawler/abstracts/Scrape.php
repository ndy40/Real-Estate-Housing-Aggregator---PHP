<?php
namespace models\crawler\abstracts;

use models\exceptions\ScrapeInitializationException;


/**
 * Description of Scrape
 *
 * @author ndy40
 */
abstract class Scrape 
{
    
    protected $type;
    
    protected $country;
    
    protected $agent;
    
    protected $command;
    
    protected $scriptName;
    
    protected $debug = false;
    
    protected $url;

    protected $output;
    
    protected $logLevel = 'error';
    
    protected $startTime;
    
    protected $completionTime;
    
    protected $configFile;
    
    
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
    public function setConfiguration ($config) {
        //initialize all parameters
        foreach($config as $key => $value) {
            $this->{$key} = $value;
        }
    }
    /**
     * The initialization method. Overrite to prepare for scrape execution.
     */
    protected function initialize () 
    {
        $this->startTime = time();
        
    }
    
    public function execute () 
    {
        $scrapeCommand = $this->buildCommand(
            $this->command,
            $this->scriptName,
            $this->country,
            $this->agent,
            $this->url,
            $this->type,
            $this->logLevel,
            $this->debug,
            $this->configFile
        );
        
        $output = shell_exec($scrapeCommand);
       
        $this->checkForErrors($output);
        
        $this->output = $this->handleResult($output);

//        $this->cleanUp();
        
        return $this->output;
        
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
        $configFile = 'config.json'
    ) 
    {
        $commandOutput = '';
        
        $commandOutput .= $command. " " . $scriptName . " " 
            . $country . " " . $agent . " " . $url 
            . " ". $type . " --log-level=" . $logLevel . " "
            . "--config=" . "'$configFile'";
        
        if ($debug) {
            $commandOutput .= " --verbose=true";
        }
        
        return $commandOutput;
    }
    /**
     * Method that is called to handle results. This will be unique to each subclass.
     * 
     * @param string $result The string (XML) representation of result.
     * @return DOMNode If the result was properly handled true is returned.
     */
    abstract protected function handleResult ($result);
    
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
    protected function queueResult($jobQueue, $result) {
        throw new Exception("Not implemented yet.");
    }
    
    /**
     * Clean up task when done. E.g Close connection and null objects for GC
     */
    protected function cleanUp() {
        throw new Exception("Not implemnted yet");
    }
}

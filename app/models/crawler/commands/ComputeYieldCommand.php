<?php 
namespace crunch;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ComputeYieldCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'crunch:yields';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Queue properties for rental yield computation.';
        
        /**
         * Property logic class.
         * 
         * @var PropertyLogic
         */
        protected $propLogic;

        /**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(\models\interfaces\DataLogicInterface $repo)
	{
            parent::__construct();
            $this->propLogic = $repo;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
            $queryString = array("offer_type" => "Sale");
<<<<<<< HEAD
            $count = $this->propLogic->searchPropertyCount('', false, $queryString);
=======
            $count = $this->propLogic->searchPropertyCount('', true, $queryString);
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
            $numberOfPages = ceil($count/25);
            
            for($i = 1; $i <= $numberOfPages; $i++) {
                $properties = $this->propLogic->searchProperty(
                    '', //empty filter
<<<<<<< HEAD
                    false, //set publish to false. Computer all. 
                    "updated_at",
                    "asc",
=======
                    true, //set publish to true. Computer all. 
                    "updated_at",
                    "",
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
                    $i,
                    25,
                    $queryString
                );

                if ($properties) {
                    $this->info("Queuing properties from Page {$i} of {$count}");
                    $this->pushToQueue($properties);
                }
            }
            
            $this->info("Properties put in YieldQueue");
	}
        
        protected function pushToQueue($properties) {
            $queuData = array ();
            foreach ($properties as $property) {
                $queuData[] = array(
                    'id' => $property->id,
                    'type_id' => $property->type_id,
                    'post_code_id' => $property->post_code_id,
                    'rooms' => $property->rooms,
                    'currentPrice' => $property->price,
                );
            }
            
            Queue::push('\models\crawler\scrape\YieldQueue', $queuData, "pc_yield");
        }
        
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
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
		);
	}

}

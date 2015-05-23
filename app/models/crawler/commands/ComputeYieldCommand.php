<?php
namespace crunch;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;
use Indatus\Dispatcher\Scheduler;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Scheduling\ScheduledCommandInterface;

class ComputeYieldCommand extends Command implements ScheduledCommandInterface
{

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
            $count = $this->propLogic->searchPropertyCount('', true, $queryString);
            $numberOfPages = ceil($count/25);

            for($i = 1; $i <= $numberOfPages; $i++) {
                $properties = $this->propLogic->searchProperty(
                    '', //empty filter
                    true, //set publish to true. Computer all.
                    "updated_at",
                    "",
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
        return $scheduler->daily()->hours([4, 23]);
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

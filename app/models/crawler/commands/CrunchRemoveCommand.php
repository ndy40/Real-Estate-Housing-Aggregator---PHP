<?php
namespace crunch;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Indatus\Dispatcher\Scheduler;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Scheduling\ScheduledCommandInterface;
use models\interfaces\PropertyRespositoryInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Carbon\Carbon;

class CrunchRemoveCommand extends Command implements ScheduledCommandInterface
{

    /**
         * The console command name.
         *
         * @var string
         */
    protected $name = 'crunch:removeold';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $propertyRepo;

    protected $description = 'Remove old products';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PropertyRespositoryInterface $propRepo)
    {
        parent::__construct();
		$this->propertyRepo = $propRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
    	$days = $this->option('days');
    	$this->info(sprintf('Deleting old properties %d days before.', $days));
		//$now = new DateTime();
		//$startdate = strtotime(sprintf('-%d days', $days), $now);
		//$startdate = strtotime(sprintf('-3 days', $days), $now);
		$startdate = Carbon::now()->subDays($days);
    	$this->propertyRepo->deleteOldProperty($startdate);
		$this->info('Success!');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('days', null, InputOption::VALUE_OPTIONAL, 'day to remove.', 7)
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
        return $scheduler->daily()->hours(4);
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

<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SendRetentionEmail extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'send:retentionemail';
    protected $description = 'Send Retention Email to Users.';
    protected $authLogic;
    protected $propertyLogic;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->authLogic = App::make('AuthLogic');
        $this->propertyLogic = App::make("PropertyLogic");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        $this->info("This command will send retention email to users.");
        $users = $this->authLogic->getActiveUserList();
        $this->info("Total User: " . count($users));
        if (count($users) > 0) {
            $highestYieldProperties = $this->propertyLogic->getPropertiesByType('HighestYield');
            $highReductionProperties = $this->propertyLogic->getPropertiesByType('HighReduction');
            // preparing email message and sending..
            foreach ($users as $user) {
                $name = $user->first_name . ' ' . $user->last_name;
                $userEmail = $user->email;
                $this->info("User: " . $name);
                $data = array('name' => $name, 'highestYieldProperties' => $highestYieldProperties, 'highReductionProperties' => $highReductionProperties);
                if (count($highestYieldProperties) > 0 || count($highReductionProperties) > 0) {
                     $this->info("Highest Yield Properties: " . count($highestYieldProperties) . " Highest Reduction Properties: " . count($highReductionProperties));
                    \Mail::send("emails.retentionweekly", $data, function ($message) use ($userEmail, $name) {
                                $message->to($userEmail, $name)->subject("Property Crunch | Properties matching your preference");
                            }
                    );
                }
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        /*
          return array(
          array('example', InputArgument::REQUIRED, 'An example argument.'),
          ); */
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return array(
            array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}

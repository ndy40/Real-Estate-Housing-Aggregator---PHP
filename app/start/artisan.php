<<<<<<< HEAD
<?php

use crunch\CrunchItemCommand;
use crunch\CrunchListCommand;
use crunch\CrunchDataCommand;
use crunch\CrunchScrapeCommand;
use crunch\ComputeYieldCommand;

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new CrunchListCommand(App::make("ScrapeFactory")));

Artisan::add(new CrunchItemCommand(App::make("ScrapeFactory")));

Artisan::add(new CrunchDataCommand(App::make("ScrapeFactory")));

Artisan::add(new CrunchScrapeCommand(App::make("AgentLogic")));

Artisan::add(new CrunchRemoveCommand(App::make("PropertyRepository")));

Artisan::add(new ClearBeanstalkdQueueCommand());
=======
<?php

use crunch\CrunchItemCommand;
use crunch\CrunchListCommand;
use crunch\CrunchFeedCommand;
use crunch\CrunchScrapeCommand;
use crunch\CrunchRemoveCommand;
use crunch\ComputeYieldCommand;
use crunch\CrunchUpdateCommand;
use models\repositories\PropertyRepository;

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new CrunchListCommand(App::make("ScrapeFactory")));

Artisan::add(new CrunchItemCommand(App::make("ScrapeFactory")));

Artisan::add(new CrunchFeedCommand(App::make("ScrapeFactory")));

Artisan::add(new CrunchScrapeCommand(App::make("AgentLogic")));

Artisan::add(new CrunchRemoveCommand(App::make("PropertyRepository")));

Artisan::add(new ComputeYieldCommand(App::make("PropertyLogic")));

Artisan::add(new CrunchUpdateCommand(App::make("AgentLogic")));
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501

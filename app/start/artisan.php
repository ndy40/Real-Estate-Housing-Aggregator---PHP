<?php

use crunch\CrunchItemCommand;
use crunch\CrunchListCommand;
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

Artisan::add(new CrunchScrapeCommand(App::make("AgentLogic")));

Artisan::add(new ComputeYieldCommand(App::make("PropertyLogic")));

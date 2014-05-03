<?php

use crunch\CrunchItemCommand;
use crunch\CrunchListCommand;
use crunch\CrunchScrapeCommand;

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

Artisan::add(new CrunchListCommand);

Artisan::add(new CrunchItemCommand);

Artisan::add(new CrunchScrapeCommand);
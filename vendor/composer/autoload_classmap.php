<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'BaseController' => $baseDir . '/app/controllers/BaseController.php',
    'CalcController' => $baseDir . '/app/controllers/CalcController.php',
    'Cartalyst\\Sentry\\Groups\\GroupExistsException' => $vendorDir . '/cartalyst/sentry/src/Cartalyst/Sentry/Groups/Exceptions.php',
    'Cartalyst\\Sentry\\Groups\\GroupNotFoundException' => $vendorDir . '/cartalyst/sentry/src/Cartalyst/Sentry/Groups/Exceptions.php',
    'Cartalyst\\Sentry\\Groups\\NameRequiredException' => $vendorDir . '/cartalyst/sentry/src/Cartalyst/Sentry/Groups/Exceptions.php',
    'Cartalyst\\Sentry\\Throttling\\UserBannedException' => $vendorDir . '/cartalyst/sentry/src/Cartalyst/Sentry/Throttling/Exceptions.php',
    'Cartalyst\\Sentry\\Throttling\\UserSuspendedException' => $vendorDir . '/cartalyst/sentry/src/Cartalyst/Sentry/Throttling/Exceptions.php',
    'Cartalyst\\Sentry\\Users\\LoginRequiredException' => $vendorDir . '/cartalyst/sentry/src/Cartalyst/Sentry/Users/Exceptions.php',
    'Cartalyst\\Sentry\\Users\\PasswordRequiredException' => $vendorDir . '/cartalyst/sentry/src/Cartalyst/Sentry/Users/Exceptions.php',
    'Cartalyst\\Sentry\\Users\\UserAlreadyActivatedException' => $vendorDir . '/cartalyst/sentry/src/Cartalyst/Sentry/Users/Exceptions.php',
    'Cartalyst\\Sentry\\Users\\UserExistsException' => $vendorDir . '/cartalyst/sentry/src/Cartalyst/Sentry/Users/Exceptions.php',
    'Cartalyst\\Sentry\\Users\\UserNotActivatedException' => $vendorDir . '/cartalyst/sentry/src/Cartalyst/Sentry/Users/Exceptions.php',
    'Cartalyst\\Sentry\\Users\\UserNotFoundException' => $vendorDir . '/cartalyst/sentry/src/Cartalyst/Sentry/Users/Exceptions.php',
    'Cartalyst\\Sentry\\Users\\WrongPasswordException' => $vendorDir . '/cartalyst/sentry/src/Cartalyst/Sentry/Users/Exceptions.php',
    'CountryTableSeeder' => $baseDir . '/app/database/seeds/CountryTableSeeder.php',
    'CreateAgencyTable' => $baseDir . '/app/database/migrations/2014_03_20_231142_create_agency_table.php',
    'CreateCountryTable' => $baseDir . '/app/database/migrations/2014_03_20_225922_create_country_table.php',
    'CreateCountyTable' => $baseDir . '/app/database/migrations/2014_03_20_232645_create_county_table.php',
    'CreateFailedJobsTable' => $baseDir . '/app/database/migrations/2014_03_29_004400_create_failed_jobs_table.php',
    'CreateFailedScrapesTable' => $baseDir . '/app/database/migrations/2014_03_29_021854_create_failed_scrapes_table.php',
    'CreatePostCodeTable' => $baseDir . '/app/database/migrations/2014_03_20_233756_create_post_code_table.php',
    'CreatePropertiesTable' => $baseDir . '/app/database/migrations/2014_03_21_000711_create_properties_table.php',
    'CreatePropertyType' => $baseDir . '/app/database/migrations/2014_03_20_235834_create_property_type.php',
    'DashController' => $baseDir . '/app/controllers/DashController.php',
    'DatabaseSeeder' => $baseDir . '/app/database/seeds/DatabaseSeeder.php',
    'HomeController' => $baseDir . '/app/controllers/HomeController.php',
    'IlluminateQueueClosure' => $vendorDir . '/laravel/framework/src/Illuminate/Queue/IlluminateQueueClosure.php',
    'MigrationCartalystSentryInstallGroups' => $vendorDir . '/cartalyst/sentry/src/migrations/2012_12_06_225929_migration_cartalyst_sentry_install_groups.php',
    'MigrationCartalystSentryInstallThrottle' => $vendorDir . '/cartalyst/sentry/src/migrations/2012_12_06_225988_migration_cartalyst_sentry_install_throttle.php',
    'MigrationCartalystSentryInstallUsers' => $vendorDir . '/cartalyst/sentry/src/migrations/2012_12_06_225921_migration_cartalyst_sentry_install_users.php',
    'MigrationCartalystSentryInstallUsersGroupsPivot' => $vendorDir . '/cartalyst/sentry/src/migrations/2012_12_06_225945_migration_cartalyst_sentry_install_users_groups_pivot.php',
    'SessionHandlerInterface' => $vendorDir . '/symfony/http-foundation/Symfony/Component/HttpFoundation/Resources/stubs/SessionHandlerInterface.php',
    'TestCase' => $baseDir . '/app/tests/TestCase.php',
    'UserController' => $baseDir . '/app/controllers/UserController.php',
    'crunch\\CrunchItemCommand' => $baseDir . '/app/models/crawler/commands/CrunchItemCommand.php',
    'crunch\\CrunchListCommand' => $baseDir . '/app/models/crawler/commands/CrunchListCommand.php',
    'models\\crawler\\JobQueue' => $baseDir . '/app/models/crawler/JobQueue.php',
    'models\\crawler\\abstracts\\Scrape' => $baseDir . '/app/models/crawler/abstracts/Scrape.php',
    'models\\crawler\\factories\\JobQueueFactory' => $baseDir . '/app/models/crawler/factories/JobQueueFactory.php',
    'models\\crawler\\factories\\ScrapeFactory' => $baseDir . '/app/models/crawler/factories/ScrapeFactory.php',
    'models\\crawler\\scrape\\ItemScrape' => $baseDir . '/app/models/crawler/scrape/ItemScrape.php',
    'models\\crawler\\scrape\\ListScrape' => $baseDir . '/app/models/crawler/scrape/ListScrape.php',
    'models\\entities\\Agency' => $baseDir . '/app/models/entities/Agency.php',
    'models\\entities\\Catalogue' => $baseDir . '/app/models/entities/Catalogue.php',
    'models\\entities\\Country' => $baseDir . '/app/models/entities/Country.php',
    'models\\entities\\County' => $baseDir . '/app/models/entities/County.php',
    'models\\entities\\FailedScrapes' => $baseDir . '/app/models/entities/FailedScrapes.php',
    'models\\entities\\PostCode' => $baseDir . '/app/models/entities/PostCode.php',
    'models\\entities\\Property' => $baseDir . '/app/models/entities/Property.php',
    'models\\entities\\PropertyType' => $baseDir . '/app/models/entities/PropertyType.php',
    'models\\exceptions\\EmptyItemException' => $baseDir . '/app/models/exceptions/EmptyItemException.php',
    'models\\exceptions\\ScrapeInitializationException' => $baseDir . '/app/models/exceptions/ScrapeInitializationException.php',
    'models\\interfaces\\MassAssignInterface' => $baseDir . '/app/models/interfaces/MassAssignInterface.php',
    'models\\interfaces\\MongoRepositoryInterface' => $baseDir . '/app/models/interfaces/MongoRepositoryInterface.php',
    'models\\interfaces\\RepositoryInterface' => $baseDir . '/app/models/interfaces/RepositoryInterface.php',
    'models\\repositories\\AgentRepository' => $baseDir . '/app/models/repositories/AgentRepository.php',
    'models\\repositories\\PropertyRepository' => $baseDir . '/app/models/repositories/PropertyRepository.php',
    'models\\repositories\\ScrapeRepository' => $baseDir . '/app/models/repositories/ScrapeRepository.php',
);

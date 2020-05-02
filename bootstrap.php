<?php

//todo make better (this must be in php.ini)
error_reporting(E_ALL);
ini_set("display_errors", 1);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', realpath(__DIR__.'/').DS);
define('APP_PATH', realpath(__DIR__.'/app/').DS);
define('CONFIG_PATH', realpath(__DIR__.'/config/').DS);
define('STORAGE_PATH', realpath(__DIR__.'/storage/').DS);
define('RESOURCES_PATH', realpath(__DIR__.'/resources/').DS);
define('PUBLIC_PATH', realpath(__DIR__.'/public/').DS);
define('LIB_PATH', realpath(__DIR__.'/lib/').DS);

require ROOT_PATH . 'vendor' . DS . 'autoload.php';

$appType = php_sapi_name() == 'cli' ? 'console' : 'http';

$settings = require CONFIG_PATH . 'app.php';
$settingsEnv = require CONFIG_PATH . ($settings['settings']['env']) . '.php';
$settings = array_merge_recursive($settings, $settingsEnv);


// instance app
$app = app($appType, $settings);

// Set up dependencies
$app->registerProviders();

// Register middleware
$app->registerMiddleware();


if ($appType == 'console') {
    // include your routes for cli requests here
    require CONFIG_PATH . 'routes' . DS . 'console.php';
} else {
    // include your routes for http requests here
    require CONFIG_PATH . 'routes' . DS . 'app.php';
}

$app->run();
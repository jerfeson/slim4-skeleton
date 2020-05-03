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

$default  = require CONFIG_PATH . 'default.php';
$env = require CONFIG_PATH . ($default['default']['env']) . '.php';
$settings = array_merge_recursive($default, $env);

if ($appType == 'console') {

    set_time_limit(0);
    $argv = $GLOBALS['argv'];
    array_shift($argv);

    // Convert $argv to PATH_INFO and mock console environment
    $settings['environment'] = Slim\Psr7\Environment::mock([
        'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'],
        'REQUEST_URI' => count($argv) >= 2 ? "/{$argv[0]}/{$argv[1]}" : "/help"
    ]);
}

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
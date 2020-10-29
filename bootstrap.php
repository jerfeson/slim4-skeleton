<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', realpath(__DIR__ . '/') . DS);
define('APP_PATH', realpath(__DIR__ . '/app/') . DS);
define('CONFIG_PATH', realpath(__DIR__ . '/config/') . DS);
define('STORAGE_PATH', realpath(__DIR__ . '/storage/') . DS);
define('RESOURCES_PATH', realpath(__DIR__ . '/resources/') . DS);
define('PUBLIC_PATH', realpath(__DIR__ . '/public/') . DS);
define('LIB_PATH', realpath(__DIR__ . '/lib/') . DS);
define('DATA_PATH', realpath(__DIR__ . '/data/') . DS);

$autoload = ROOT_PATH . 'vendor' . DS . 'autoload.php';

if (!file_exists($autoload)) {
    throw new Exception("Please install the project dependencies using the command (composer install or composer install --no-dev)");
}

require $autoload;

$appType = php_sapi_name() == 'cli' ? 'console' : 'http';

$default = require CONFIG_PATH . 'default.php';
$env = require CONFIG_PATH . ($default['default']['env']) . '.php';
$settings = array_merge_recursive($default, $env);

// instance app
$app = app($appType, $settings);

// Set up dependencies
$app->registerProviders();

// Register middleware
$app->registerMiddleware();


if ($appType == 'console') {
    return $app;
}

// include your routes for http requests here
require CONFIG_PATH . 'routes' . DS . 'app.php';

$app->prepare();

$app->run();

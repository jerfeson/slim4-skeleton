<?php

if (strtolower(getenv('APP_ENV') == 'development')) {
    define('C3_CODECOVERAGE_ERROR_LOG_FILE', '/storage/logs/c3_error.log'); //Optional (if not set the default c3 output dir will be used)
    include '../c3.php';
    define('MY_APP_/**/STARTED', true);
}

use App\App;

chdir(dirname(__DIR__));

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    throw new RuntimeException(
        'Please install the project dependencies using '
        . 'the command (composer install or composer install --no-dev)'
    );
}

include __DIR__ . '/../vendor/autoload.php';

App::bootstrap();

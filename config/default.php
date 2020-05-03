<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$default = [];

$default['default'] = [
    'env' => \Lib\Framework\App::getAppEnv(),
    'addContentLengthHeader' => false,
    // default timezone & locale
    'timezone' => 'America/Sao_Paulo',
    'locale' => 'pt_BR',
];

// Timezone
date_default_timezone_set($default['default']['timezone']);

// log file path
$default['log'] = [
    // log file path
    'file' => STORAGE_PATH . 'logs' . DS . 'app_' . date('Ymd') . '.log',
];

// template folders
$default['twig'] = [
    'path' => RESOURCES_PATH . 'views',
    'templates' => [
        'error' => RESOURCES_PATH . 'views' . DS . 'http' . DS . 'error',
        'console' => RESOURCES_PATH . 'views' . DS . 'console',
        'site' => RESOURCES_PATH . 'views' . DS . 'http' . DS . 'site',
        'mail' => RESOURCES_PATH . 'views' . DS . 'mail',
    ],
    'settings' => [
        'cache' => STORAGE_PATH . 'twig',
        'debug' => true,
        'auto_reload' => true,
    ],
];

//session
$default['session'] = [
    'name' => 'app',
    'lifetime' => 7200,
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => true,
    'cache_limiter' => 'nocache',
    'filesPath' => STORAGE_PATH . 'sessions',
];

// storage settings
$default['filesystem'] = [
    'local' => [
        'driver' => 'local',
        'root' => STORAGE_PATH,
    ],
    'ftp' => [
        'driver' => 'ftp',
        'host' => '',
        'username' => '',
        'password' => '',
        'port' => 21,
        'root' => '/',
        'passive' => true,
        'ssl' => false,
        'timeout' => 30,
    ],
];

//PHP Mailer
$default['mail'] = [
    'default' => [
        'host' => '',
        'port' => 25,
        'secure' => '',
        'username' => '',
        'password' => '',
        'from' => '',
        'fromName' => '',
        'replyTo' => '',
    ],
];

$default['providers'] = [
    App\ServiceProviders\Monolog::class => 'http,console',
    App\ServiceProviders\SlashTrace::class => 'http',
    App\ServiceProviders\Twig::class => 'http',
    App\ServiceProviders\Flash::class => 'http',
    App\ServiceProviders\Eloquent::class => 'http,console',
    App\ServiceProviders\FileSystem::class => 'http,console',
    App\ServiceProviders\Mailer::class => 'http,console',
];

// add your middleware here
$default['middleware'] = [
    App\Middleware\Session::class => 'http',
    App\Middleware\Flash::class => 'http',
];

// add your middleware here
$default['commands'] = [
    App\Console\ExampleCommand::class,
    App\Console\MigrationsCommand::class,
    App\Console\SchemaDumpCommand::class,
];

return $default;

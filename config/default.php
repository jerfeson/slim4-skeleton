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

//oAuth2
$default['oauth2'] = [
    'private_key' => file_get_contents(DATA_PATH . 'keys' . DS . 'oauth' . DS . 'private.key'),
    'public_key' => file_get_contents(DATA_PATH . 'keys' . DS . 'oauth' . DS . 'public.key'),
];

$default['settings']['cache'][] = [
    'default' => [
        'driver' => 'redis',
        'scheme' => 'tcp',
        'host' => 'redis',
        'port' => 6379,
        'database' => 0,
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
    App\ServiceProviders\OAuthServer::class => 'http,console',
    App\ServiceProviders\Validator::class => 'http,console',
    App\ServiceProviders\Csrf::class => 'http,console',
];

// add your middleware here
$default['middleware'] = [
    App\Middleware\Session::class => 'http',
    App\Middleware\Csrf::class => 'http',
    App\Middleware\OAuth::class => 'http',
    App\Middleware\Flash::class => 'http',
];

//Migration
$defaultCommands = [];
if (\Lib\Framework\App::getAppEnv() === \Lib\Framework\App::DEVELOPMENT) {
    $migrations = scandir(MIGRATION_PATH);

    foreach ($migrations as $migration) {
        if ($migration === '.' || $migration === '..' ||  $migration === 'Seeder') {
            continue;
        }
        $defaultCommands[] = "App\\Console\Migration\\" . pathinfo($migration, PATHINFO_FILENAME);
    }
};

// add your  custom commands here
$customCommands = [
    App\Console\SchemaDumpCommand::class,
    App\Console\Migration\Seeder\DatabaseSeeder::class,
];

$default['commands'] = array_merge($defaultCommands, $customCommands);

return $default;

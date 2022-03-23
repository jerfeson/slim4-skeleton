<?php

use App\App;
use App\Factory\FileSystem;
use App\Factory\Mailer;
use App\Factory\Csrf;
use App\Factory\AuthorizationServerFactory;



$config = [];

$config['default'] = [
    'env' => App::getAppEnv(),
    'display_error_details' => App::isDevelopment(),
    'timezone' => 'America/Sao_Paulo',
    'locale' => 'pt_BR',
    'encoding' => 'utf8',
];

// log file path
$config['log'] = [
    // log file path
    'aa' => 'aa',
    'file' => STORAGE_PATH . 'logs' . DS . 'app_' . date('Ymd') . '.log',
];

// template folders
$config['twig'] = [
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
$config['session'] = [
    'name' => 'awesome_invoice',
    'lifetime' => 7200,
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => true,
    'cache_limiter' => 'nocache',
    'filesPath' => STORAGE_PATH . 'sessions',
];

// storage settings
$config['filesystem'] = [
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
$config['mail'] = [
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
$config['oauth2'] = [
    'private_key' => file_get_contents(DATA_PATH . 'keys' . DS . 'oauth' . DS . 'private.key'),
    'public_key' => file_get_contents(DATA_PATH . 'keys' . DS . 'oauth' . DS . 'public.key'),
];

$config['settings']['cache'][] = [
    'default' => [
        'driver' => 'redis',
        'scheme' => 'tcp',
        'host' => 'redis',
        'port' => 6379,
        'database' => 0,
    ],
];

$config['providers'] = [
    FileSystem::class => 'http,console',
    Mailer::class => 'http,console',
    AuthorizationServerFactory::class => 'http,console',
    Csrf::class => 'http,console',
];

return $config;

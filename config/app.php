<?php

return [
    'settings' => [
        'env' => \Lib\Framework\App::getAppEnv(),
        'addContentLengthHeader' => false,
        // default timezone & locale
        'timezone' => 'America/Sao_Paulo',
        'locale' => 'pt_BR',
        // log file path
        'log' => [
            // log file path
            'file' => STORAGE_PATH . "logs" . DS . "app_" . date('Ymd') . ".log",
        ],
        // template folders
        'twig' =>
            [
                'path' => RESOURCES_PATH . "views",
                'templates' => [
                    'error' => RESOURCES_PATH . "views" . DS . "http" . DS . "error",
                    'console' => RESOURCES_PATH . "views" . DS . "console",
                    'site' => RESOURCES_PATH . "views" . DS . "http" . DS . "site",
                    'mail' => RESOURCES_PATH . "views" . DS . "mail",
                ],
                'settings' => [
                    'cache' => STORAGE_PATH . 'twig',
                    'debug' => true,
                    'auto_reload' => true,
                ]
            ],
        'session' => [
            'name' => 'app',
            'lifetime' => 7200,
            'path' => '/',
            'domain' => null,
            'secure' => false,
            'httponly' => true,
            'cache_limiter' => 'nocache',
            'filesPath' => STORAGE_PATH . 'sessions',
        ],
    ],
    // add your service providers here
    'providers' => [
        App\ServiceProviders\Monolog::class => 'http,console',
        App\ServiceProviders\SlashTrace::class => 'http,console',
        App\ServiceProviders\Twig::class => 'http',
        App\ServiceProviders\Flash::class => 'http',
    ],
    // add your middleware here
    'middleware' => [
        App\Middleware\Session::class => 'http,console',
    ],

];

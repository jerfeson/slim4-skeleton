<?php

$default['default']['debug'] = true;
$default['default']['baseUrl'] = 'http://localhost:8080/';
$default['default']['indexFile'] = true;

return [
    'settings' => [
        'database' => [
            // default db connection settings
            'default' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'database' => 'default',
                'username' => '',
                'password' => '',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
            ],
        ],
        'cache' => [
            'default' => [
                'driver' => 'redis',
                'scheme' => 'tcp',
                'host' => 'redis',
                'port' => 6379,
                'database' => 0,
            ],
        ],
    ],
];

<?php

$default['default']['debug'] = true;
$default['default']['baseUrl'] = 'http://local.slim4/';
$default['default']['indexFile'] = true;

return [
    'settings' => [
        'database' => [
            // default db connection settings
            'default' => [
                'driver' => 'mysql',
                'host' => 'mysql',
                'database' => 'default',
                'username' => 'root',
                'password' => 'root',
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

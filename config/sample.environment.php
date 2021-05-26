<?php

$default['default']['debug'] = true;
$default['default']['baseUrl'] = 'http://localhost:8082';
$default['default']['indexFile'] = true;

return [
    'settings' => [
        'database' => [
            // default db connection settings
            'default' => [
                'driver' => 'mysql',
                'host' => 'mysql',
                'database' => 'skeleton',
                'username' => 'root',
                'password' => 'root',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
            ],
        ],
    ],
];

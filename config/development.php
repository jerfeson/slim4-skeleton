<?php

return [
    'settings' => [
        // debug options
        'debug' => true,
        // url config. Url must end with a slash '/'
        'baseUrl' => 'http://localhost:8080/',
        'indexFile' => true,
        // database configs
        'database' => [
            // default db connection settings
            'default' => [
                'driver'    => 'mysql',
                'host'      => 'mysql',
                'database'  => 'default',
                'username'  => 'root',
                'password'  => 'root',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => ''
            ],
        ],
        'cache' => [
            'default' => [
                'driver' 	=> 'redis',
                'scheme' 	=> 'tcp',
                'host' 		=> 'redis',
                'port' 		=> 6379,
                'database' 	=> 0,
            ]
        ]
    ]
];
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
    'private_key' => '-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEAucICIOSQ51bOd92YYR1mnTWs8s1rz4K4QZ1VFYM67PeG99me
Us2O4qin2P+tOPitwjBgvfI5F9gL6AkHnyDzmlXYwkLMZGo1GqqcmKDyPsw3kpx+
TzEzXoAwo5+H6SOAWC5gHiUIzB1TM5tr/DaDHbSEZ7w7LJs7f3ygw175XllM9W7J
gKuy2WPkCi3Y/oGNh4mhX34Ziqe1dPnII+DTBrxG6AyTbF0dXO1NFacoFYv93mgX
JRPaSgXB/E0GGZE7p9ylKS9RHzHOHdoG9bWtfBemLl7WdMEJADNTE12v+GA2//1Z
G+0z6f4OdArKD0hh2WV31naCF/9QI+iU/O30FwIDAQABAoIBAGzMXhTH6rskk7+P
Eb3ny39OveJkQL4EsUj1Dq2P9EZw4CNw2ZNkBElrQQwPDHUu6g6v6Sm3oor9plOc
XlNsqGF/4Ho/R5mUv0/EF/4pXlk7oV0EvdJCXfT+nf5n5OG/Ql/jobZmOJXcoyu5
33CUuQz/+wELNZl1vk54P2zOxr3kDdDenAZLlIfMfn5jv+iGR0uoAL5tmpgBRCNM
YvuKool6a7WMito8R1Ffr89cwIuF8JaoEkscaYeh1Bh4GJQmZ6jZngT8SE2smHM2
vunoZ0F3k/rbnXbWZodji0tXYNjeZIC86x7WMVhbm26wDI2d4zqbspqyAii5WMe3
0BtrcOkCgYEA6t+uxyDShOT20xMe6KYErWhgbNkJlNtDTONBg8dhWpDZyyg8a4dN
wj4knI/Fv80UZZqjkvl5T1zApfZiBOnoW9vg/6TQqtvRfJNzbCynijJ/tfFZnfgq
EA1RwYA+4NTsZAlSB1mVoc2cCd6mk74VW9JZWWy0zhY/DZ0/ABzwnosCgYEAyndb
0UhUFrQDmLEz6UteOzsoFQKRZxwJHKLDUO7xwTHfKuJx6ODYGOqtSMaD7L8OkrEg
PuwMJ/fHQWVOq39q55QBheaUVBkZtBSUg9ogXtu35rmeUXycU0/nVUB7zCtEaOUD
SbshaItXAiJL7zYt3NCWKHDqVVTmbnFX7knQXiUCgYEAs9Y5ePbEukSmSM+nJvOX
OT9mNXGpSHTqfwCytTgAbtviJw/T03FyNHxohAgBne5k47cX7/1cyUt3ppuUSbbf
1xXwnU2RCIHvULeF9Rnr3oD0EFeQCshtTIUmSEbt3jCqH56TVFVfoNhR1azJVu6A
ZCIXj8UQW4vlE4OHLYr7IikCgYB5yb6Db1yVo4ceTEmLpfhy7Ky5pqfMPEMG5KlU
f8cQjW1OoJQgn0+d/VxrEG6+9FZRyxY4g0j0qclD/yqJYsXts0wPPZov1EFv34lO
nuEl7kj7EuC255wpSUFAON+++q/V4RxPN69q8ZZPBE5lAFQqJZaNkQ6EceOCv89C
2UfruQKBgQC0r+m72wFNvs26QKXqpMMe5SA//vzoNsxQNIxV3YmV81Hij42skggT
/14ve9/FwlPgx3V+kMv5I5BSVGnV+HjbfQ/v6LYdWnYVtP737QL7MYfJbw4Ksc8o
OClim+4YtPJ+gF7ggGPdtJgjqRJypjkSJXEYo5TGT+8yO8riOQ7lsA==
-----END RSA PRIVATE KEY-----',
    'public_key' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAucICIOSQ51bOd92YYR1m
nTWs8s1rz4K4QZ1VFYM67PeG99meUs2O4qin2P+tOPitwjBgvfI5F9gL6AkHnyDz
mlXYwkLMZGo1GqqcmKDyPsw3kpx+TzEzXoAwo5+H6SOAWC5gHiUIzB1TM5tr/DaD
HbSEZ7w7LJs7f3ygw175XllM9W7JgKuy2WPkCi3Y/oGNh4mhX34Ziqe1dPnII+DT
BrxG6AyTbF0dXO1NFacoFYv93mgXJRPaSgXB/E0GGZE7p9ylKS9RHzHOHdoG9bWt
fBemLl7WdMEJADNTE12v+GA2//1ZG+0z6f4OdArKD0hh2WV31naCF/9QI+iU/O30
FwIDAQAB
-----END PUBLIC KEY-----',
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

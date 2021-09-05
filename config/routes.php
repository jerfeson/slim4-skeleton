<?php

use App\Http\Api\v1\Auth\Token;
use App\Http\Site\Welcome;
use App\Middleware\ApiAuthentication;
use App\Routing\ApiRouteResolver;
use Slim\App;

return function (App $app) {
    $app->get('/', Welcome::class)->setArgument('action', 'index');

    $app->any(
        '/api/v{version:[1]{1}}/auth/token',
        Token::class
    )->setArgument('action', 'post');

    $app->any(
        '/api/v{version:[1]{1}}/{resource:[a-z-]+}[/{child:[a-z-]+}[/{id:[0-9]+}]]',
        ApiRouteResolver::class
    )->add(ApiAuthentication::class);

    //    $app->any('/api/v{version:[1]{1}}/{resource:[a-z-]+}[/{id:[0-9]+}]', ApiRouteResolver::class);
};

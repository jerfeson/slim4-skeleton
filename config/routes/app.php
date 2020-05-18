<?php

use Codeception\Util\FileSystem;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// example route to resolve request to uri '/' to \\App\\Http\\Site\\Welcome::index
$app->any('/', function (Request $request, Response $response, $args) use ($app) {
    return $app->resolveRoute('Welcome', 'index', $args, '\App\Http\Site');
});

$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

// example route to resolve request to that matches '/{class}/{method}'
// resolveRoute will try to find a corresponding class::method in a given namespace
$app->any('/{class}/{method}', function (Request $request, Response $response, $args) use ($app) {
    return $app->resolveRoute($args['class'], $args['method'], $args, '\App\Http\Site');
});

/*API ROUTE*/
$app->any('/api/v1/{module}/{class}/{method}', function (Request $request, Response $response, $args) use ($app) {
    $nameSpace = "\App\Http\Api\V1\\" . ucfirst($args['module']);
    $method = $args['method'] . 'Action';
    $class = ucfirst($args['class']);

    return $app->resolveRoute($class, $method, $args, $nameSpace);
});
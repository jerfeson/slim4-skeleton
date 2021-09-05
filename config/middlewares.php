<?php

use App\Middleware\Flash;
use App\Middleware\Session;
use Slim\App;

return function (App $app) {
    $app->add(new Flash());
    $app->add(new Session());
};

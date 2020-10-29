<?php

namespace App\ServiceProviders;

use Slim\Csrf\Guard;

/**
 * Class Csrf.
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since 1.1.0
 *
 * @version 1.1.0
 */
class Csrf implements ProviderInterface
{
    public const PREFIX = 'csrf_';

    public static function register()
    {
        $responseFactory = app()->getResponseFactory();
        app()->getContainer()->set(Guard::class, function () use ($responseFactory) {
            $guard = new Guard($responseFactory, self::PREFIX);
            $guard->generateToken();

            return $guard;
        });
    }
}

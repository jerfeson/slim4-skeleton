<?php

namespace App\Factory;

use Psr\Container\ContainerInterface;
use Slim\Csrf\Guard;

/**
 * Class Csrf.
 *
 * @author  Jerfeson Souza Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class Csrf implements FactoryInterface
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

    public static function create(ContainerInterface $container)
    {
        // TODO: Implement create() method.
    }
}

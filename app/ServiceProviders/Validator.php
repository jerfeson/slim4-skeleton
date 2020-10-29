<?php

namespace App\ServiceProviders;

/**
 * Class Validator.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.1.0
 *
 * @version 1.1.0
 */
class Validator implements ProviderInterface
{
    public static function register()
    {
        app()->getContainer()->set(\jerfeson\Validation\Validator::class, function ($c) {
            return new \jerfeson\Validation\Validator();
        });
    }
}

<?php

namespace App\ServiceProviders;

use Slim\Flash\Messages;

/**
 * Class Flash
 * @package App\ServiceProviders
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 * @since   1.0.0
 * @version 1.0.0
 */
class Flash implements ProviderInterface
{

    public static function register()
    {
        session_start();
        $flash = new Messages();
        app()->getContainer()->set(Messages::class, $flash);
    }
}
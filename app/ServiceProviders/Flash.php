<?php

namespace App\ServiceProviders;

use Slim\Flash\Messages;

/**
 * Class Flash
 * @package App\ServiceProviders
 * @author  Jerfeson Guerreiro <jerfeson@codeis.com.br>
 * @since   1.0.0
 * @version 1.0.0
 */
class Flash implements ProviderInterface
{

    public static function register()
    {
        $flash = new Messages();
        app()->getContainer()->set(Messages::class, $flash);
    }
}
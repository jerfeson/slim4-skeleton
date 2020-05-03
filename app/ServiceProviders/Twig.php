<?php

namespace App\ServiceProviders;

use App\Twig\FilesystemLoader;
use Twig\Extension\DebugExtension;

/**
 * Class Twig.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class Twig implements ProviderInterface
{
    public static function register()
    {
        app()->getContainer()->set(\Slim\Views\Twig::class, function () {
            $settings = app()->getConfig('twig');
            $loader = new FilesystemLoader($settings['templates']);
            $twig = new \Slim\Views\Twig($loader, $settings['settings']);
            $twig->addExtension(new DebugExtension());

            return $twig;
        });
    }
}

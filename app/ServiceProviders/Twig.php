<?php

namespace App\ServiceProviders;

use App\Twig\AppExtension;
use App\Twig\CsrfExtension;
use App\Twig\FilesystemLoader;
use Lib\Framework\App;
use Lib\Utils\Session;
use Slim\Csrf\Guard;
use Twig\Extension\DebugExtension;

/**
 * Class Twig.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.1.0
 */
class Twig implements ProviderInterface
{
    public static function register()
    {
        app()->getContainer()->set(\Slim\Views\Twig::class, function () {
            $settings = app()->getConfig('twig');
            $loader = new FilesystemLoader($settings['templates']);
            $twig = new \Slim\Views\Twig($loader, $settings['settings']);
            $guard = app()->getContainer()->get(Guard::class);

            $twig->addExtension(new DebugExtension());
            $twig->addExtension(new AppExtension());
            $twig->addExtension(new CsrfExtension($guard));

            //Global vars
            $twig->getEnvironment()->addGlobal('app_session', Session::get('user'));
            $version = '1.0.0';

            if (app()->getConfig('default.env') === App::DEVELOPMENT) {
                $version = time();
            }

            $twig->getEnvironment()->addGlobal('app_version', $version);

            return $twig;
        });
    }
}

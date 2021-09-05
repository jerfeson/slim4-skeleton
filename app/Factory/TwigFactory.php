<?php

namespace App\Factory;

use App\App;
use App\Config;
use App\Twig\AppExtension;
use Psr\Container\ContainerInterface;
use Twig\Error\LoaderError;
use Twig\Extension\DebugExtension;

/**
 * Class TwigFactory.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class TwigFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @throws LoaderError
     *
     * @return \Slim\Views\Twig
     */
    public static function create(ContainerInterface $container)
    {
        $config = $container->get(Config::class)->get('twig');
        $twig = \Slim\Views\Twig::create($config['templates'], $config['settings']);
        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new AppExtension());
        $twig->getEnvironment()->addGlobal('app_version', App::getVersion());

        return $twig;
    }
}

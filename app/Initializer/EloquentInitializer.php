<?php

namespace App\Initializer;

use App\App;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;

/**
 * Class EloquentInitializer.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class EloquentInitializer implements InitializerInterface
{
    /**
     * @param ContainerInterface $container
     */
    public static function initialize(ContainerInterface $container)
    {
        $settings = App::getConfig()->get('database');

        $capsule = new Capsule();
        foreach ($settings as $name => $configs) {
            $capsule->addConnection($configs, $name);
        }

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}

<?php

namespace App\Factory;

use App\App;
use Naroga\RedisCache\Redis;
use Predis\Client;
use Psr\Container\ContainerInterface;

/**
 * Class Cache.
 *
 * @author  Jerfeson Souza Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class Cache implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @return Redis
     */
    public static function create(ContainerInterface $container)
    {
        $settings = App::getConfig()->get('settings.cache');

        return new Redis(new Client($settings['default']));
    }
}

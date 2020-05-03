<?php

namespace App\ServiceProviders;

use Naroga\RedisCache\Redis;
use Predis\Client;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Cache.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class Cache implements ProviderInterface
{
    public static function register()
    {
        app()->getContainer()->set(CacheInterface::class, function () {
            $settings = app()->getConfig('settings.cache');

            return new Redis(new Client($settings['default']));
        });
    }
}

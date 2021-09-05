<?php

namespace App\Factory;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;
use Psr\Container\ContainerInterface;

/**
 * Class DbConnectionFactory.
 *
 * @author Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class DbConnectionFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @return Connection
     */
    public static function create(ContainerInterface $container)
    {
        return Manager::connection('default');
    }
}

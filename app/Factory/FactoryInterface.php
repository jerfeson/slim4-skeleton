<?php

namespace App\Factory;

use Psr\Container\ContainerInterface;

/**
 * Interface FactoryInterface.
 *
 * @author Thiago Daher <thiago_tsda@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
interface FactoryInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @return mixed
     */
    public static function create(ContainerInterface $container);
}

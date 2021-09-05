<?php

namespace App\Factory;

use DI\Factory\RequestedEntry;
use Psr\Container\ContainerInterface;

/**
 * Interface GenericFactoryInterface.
 *
 * @author Thiago Daher <thiago_tsda@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
interface GenericFactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param RequestedEntry     $entry
     *
     * @return mixed
     */
    public static function create(ContainerInterface $container, RequestedEntry $entry);
}

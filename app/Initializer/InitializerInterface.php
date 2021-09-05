<?php

namespace App\Initializer;

use Psr\Container\ContainerInterface;

/**
 * Interface InitializerInterface
 * Represents a service that initializes some important functionality of the application, before it starts.
 *
 * @author Thiago Daher
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
interface InitializerInterface
{
    /**
     * Inicializa a funcionalidade.
     *
     * @param ContainerInterface $container
     */
    public static function initialize(ContainerInterface $container);
}

<?php

namespace Console;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

abstract class Console extends Command
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * ExampleCommand constructor.
     *
     * @param ContainerInterface $container
     * @param string|null        $name
     */
    public function __construct(ContainerInterface $container, ?string $name = null)
    {
        parent::__construct($name);
        $this->container = $container;
    }
}

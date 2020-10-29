<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

if (isset($_SERVER['REQUEST_METHOD'])) {
    echo "Only CLI allowed. Script stopped.\n";
    exit(1);
}

/** @var ContainerInterface $container */
$app = (require 'bootstrap.php');
$container = $app->getContainer();
$commands = $app->getConfig('commands');
$application = new Application();

foreach ($commands as $class) {
    $application->add($container->get($class));
}

$application->run();

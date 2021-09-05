<?php

namespace App\Factory;

use App\App;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LogLevel;

/**
 * Class MonologFactory.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 */
class MonologFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @return Logger
     */
    public static function create(ContainerInterface $container)
    {
        $logFilePath = $logFilePath ?? App::getConfig()->get('log.file');

        $logger = new Logger(App::getAppType());
        $formatter = new LineFormatter(null, null, true);
        $formatter->includeStacktraces(false);
        $handler = new StreamHandler($logFilePath, LogLevel::DEBUG);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);

        return $logger;
    }
}

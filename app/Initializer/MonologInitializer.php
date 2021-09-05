<?php

namespace App\Initializer;

use App\App;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LogLevel;

/**
 * Class MonologInitializer.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class MonologInitializer implements InitializerInterface
{
    /**
     * @param ContainerInterface $container
     */
    public static function initialize(ContainerInterface $container)
    {
        $appName = App::isConsole() ? 'console' : 'http';
        $logFilePath = $logFilePath ?? App::getConfig()->get('log.file');

        $logger = new Logger($appName);

        if (!empty($logFilePath)) {
            $formatter = new LineFormatter(null, null, true);
            $formatter->includeStacktraces(false);

            $handler = new StreamHandler($logFilePath, LogLevel::DEBUG);
            $handler->setFormatter($formatter);
            $logger->pushHandler($handler);
        }
    }
}

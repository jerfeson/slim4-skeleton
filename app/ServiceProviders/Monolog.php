<?php

namespace App\ServiceProviders;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class Monolog
 * @package App\ServiceProviders
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 * @since   1.0.0
 * @version 1.0.0
 */
class Monolog implements ProviderInterface
{

    public static function register()
    {
        $app = app();
        $appName = $app->appType;
        $logFilePath = $logFilePath ?? $app->getConfig("settings.log.file");

        $logger = new Logger($appName);

        if (!empty($logFilePath)) {
            $formatter = new LineFormatter(null, null, true);
            $formatter->includeStacktraces(false);

            $handler = new StreamHandler($logFilePath, LogLevel::DEBUG);
            $handler->setFormatter($formatter);
            $logger->pushHandler($handler);
        }

        $logFilePath = $logFilePath ?? $app->getConfig("settings.log.file");

        $logger = new Logger($appName);
        $formatter = new LineFormatter(null, null, true);
        $formatter->includeStacktraces(false);
        $handler = new StreamHandler($logFilePath, LogLevel::DEBUG);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);

        $app->getContainer()->set(LoggerInterface::class, $logger);
    }

}
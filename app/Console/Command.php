<?php

namespace App\Console;

use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

/**
 * Class Command
 * @package App\Console
 * @author  Jerfeson Guerreiro <jerfeson@codeis.com.br>
 * @since   1.0.0
 * @version 1.0.0
 */
abstract class Command
{
    /**
     * @var Twig
     */
    private $view;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Command constructor.
     * @param Twig $view
     * @param LoggerInterface $logger
     */
    public function __construct(
        Twig $view,
        LoggerInterface $logger)
    {
        $this->setLogger($logger);
        $this->setView($view);
    }

    /**
     * @return Twig
     */
    public function getView(): Twig
    {
        return $this->view;
    }

    /**
     * @param Twig $view
     */
    public function setView(Twig $view): void
    {
        $this->view = $view;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
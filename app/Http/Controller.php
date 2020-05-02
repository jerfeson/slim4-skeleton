<?php

namespace App\Http;

use Psr\Log\LoggerInterface;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class Controller
 * @package App\Http
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 * @since   1.0.0
 * @version 1.0.0
 */
abstract class Controller
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Response
     */
    private $response;
    /**
     * @var Twig
     */
    private $view;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Messages
     */
    private $flash;

    /**
     * Controller constructor.
     * @param Request $request
     * @param Response $response
     * @param Twig $view
     * @param LoggerInterface $logger
     * @param Messages $flash
     */
    public function __construct(
        Request $request,
        Response $response,
        Twig $view,
        LoggerInterface $logger,
        Messages $flash
    )
    {
        $this->setRequest($request);
        $this->setResponse($response);
        $this->setView($view);
        $this->setLogger($logger);
        $this->setFlash($flash);
    }

    /**
     * @param Request $request
     */
    private function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @param Response $response
     */
    private function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    /**
     * @param Twig $view
     */
    private function setView(Twig $view): void
    {
        $this->view = $view;
    }

    /**
     * @param LoggerInterface $logger
     */
    private function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @param Messages $flash
     */
    private function setFlash(Messages $flash): void
    {
        $this->flash = $flash;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return Twig
     */
    public function getView(): Twig
    {
        return $this->view;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return Messages
     */
    public function getFlash(): Messages
    {
        return $this->flash;
    }
}
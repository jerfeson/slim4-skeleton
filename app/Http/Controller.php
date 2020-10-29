<?php

namespace App\Http;

use App\Helpers\Payload\Payload;
use App\Message\Message;
use App\Validation\Validator;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Slim\Views\Twig;

/**
 * Class Controller.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
abstract class Controller
{
    protected $businessClass;

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
     * @var mixed
     */
    private $business;

    /**
     * @var AuthorizationServer
     */
    private $oAuthServer;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var Guard
     */
    private $csrf;


    /**
     * Controller constructor.
     * @param Request $request
     * @param Response $response
     * @param Twig $view
     * @param LoggerInterface $logger
     * @param Messages $flash
     * @param AuthorizationServer $oAuthServer
     * @param Validator $validator
     * @param Guard $csrf
     */
    public function __construct(
        Request $request, Response $response,
        Twig $view,
        LoggerInterface $logger,
        Messages $flash,
        AuthorizationServer $oAuthServer,
        Validator $validator,
        Guard $csrf
    )
    {
        $this->setRequest($request);
        $this->setResponse($response);
        $this->setView($view);
        $this->setLogger($logger);
        $this->setFlash($flash);
        $this->setOAuthServer($oAuthServer);
        $this->setValidator($validator);
        $this->setCsrf($csrf);
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

    /**
     * @return AuthorizationServer
     */
    public function getOAuthServer(): AuthorizationServer
    {
        return $this->oAuthServer;
    }

    /**
     * @param AuthorizationServer $oAuthServer
     */
    public function setOAuthServer(AuthorizationServer $oAuthServer): void
    {
        $this->oAuthServer = $oAuthServer;
    }

    /**
     * @return Validator
     */
    public function getValidator(): Validator
    {
        return $this->validator;
    }

    /**
     * @param Validator $validator
     */
    public function setValidator(Validator $validator): void
    {
        $this->validator = $validator;
    }

    /**
     * @return Guard
     */
    public function getCsrf(): Guard
    {
        return $this->csrf;
    }

    /**
     * @param Guard $csrf
     */
    public function setCsrf(Guard $csrf): void
    {
        $this->csrf = $csrf;
    }

    /**
     * @return mixed
     * @throws Exception
     *
     * @throws Exception
     *
     */
    protected function getBusiness()
    {
        if (!$this->businessClass) {
            throw new Exception(Message::BUSINESS_CLASS_NOT_DEFINED);
        }

        if (!$this->business) {
            $this->business = new $this->businessClass($this->getRequest(), $this->getResponse());
        }

        return $this->business;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    protected function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param array|object|null $data
     * @return Response
     */
    protected function respondWithData($data = null, int $statusCode = 200): Response
    {
        $payload = new Payload($statusCode, $data);

        return $this->response($payload);
    }

    /**
     * @param Payload $payload
     * @return Response
     */
    protected function response(Payload $payload): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->getResponse()->getBody()->write($json);

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($payload->getStatusCode());
    }
}

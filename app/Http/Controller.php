<?php

namespace App\Http;

use App\Payload\PayloadInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpNotFoundException;

/**
 * Class Controller.
 *
 * @author  Thiago Daher
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
abstract class Controller
{
    private ?string $id;

    private Response $response;

    private Request $request;

    private array $routeParams;

    /**
     * Processa os parâmetros da rota e chama o método xxxxAction correto.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @throws HttpException
     *
     * @return Response
     */
    final public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->routeParams = $args;
        $this->request = $request;
        $this->response = $response;

        $action = $args['action'] ?? 'index';
        $action .= isset($args['version']) ? 'Action' : '';

        if (!method_exists($this, $action)) {
            throw $this->actionNotFound($request);
        }

        $this->id = $args['id'] ?? '';

        return $this->{$action}();
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    /**
     * Retorna o id do recurso ou entidade que o controller irá manipular.
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Slim\Exception\HttpException
     */
    protected function actionNotFound(Request $request)
    {
        return new HttpNotFoundException($request);
    }

    /**
     * Retorna o BODY da requisição em formato JSON.
     *
     * @throws HttpBadRequestException
     *
     * @return array
     */
    protected function getJsonBody(): array
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpBadRequestException($this->request);
        }

        return $input;
    }

    protected function respondWithJson(array $data, int $statusCode = 200): Response
    {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->getResponse()
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode)
        ;
    }

    protected function respondWithPayload(PayloadInterface $payload, int $statusCode = 200): Response
    {
        return $this->respondWithJson($payload->toArray(), $statusCode);
    }
}

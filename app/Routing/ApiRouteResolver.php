<?php

namespace App\Routing;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

/**
 * Class ApiRouteResolver
 * Service responsible for processing an API route and directing the command to the correct controller.
 *
 * @author  Thiago Daher
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class ApiRouteResolver
{
    private ContainerInterface $container;
    private string $controllerClass = '';

    /**
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Processa a rota e chama o controller correto.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @throws \Slim\Exception\HttpNotFoundException
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        if (!$this->isRouteValid($args)) {
            throw new HttpNotFoundException($request);
        }

        return $this->dispatchToController($request, $response, $args);
    }

    /**
     * @param array $args
     *
     * @return bool
     */
    public function isRouteValid(array $args): bool
    {
        $this->parseRouteArgs($args);

        return !empty($this->controllerClass) && class_exists($this->controllerClass);
    }

    /**
     * @param array $args
     */
    private function parseRouteArgs(array $args)
    {
        $this->controllerClass = '';

        if (!isset($args['version']) || !isset($args['resource'])) {
            return;
        }

        $this->controllerClass = $this->getResourceClass($args['version'], $args['resource']);

        if (isset($args['child'])) {
            $this->controllerClass .= '\\' . $this->getResourceName($args['child']);
        }
    }

    /**
     * Obtém a classe de controller relacionada ao recurso especificado.
     *
     * @param string $version
     * @param string $resource
     *
     * @return string
     */
    private function getResourceClass(string $version, string $resource): string
    {
        return sprintf('\\App\\Http\\Api\\V%s\\%s', $version, $this->getResourceName($resource));
    }

    /**
     * Converte o nome do recurso no formato "nome-recurso" para um
     * nome de classe no formato "NomeRecurso".
     *
     * @param string $resource
     *
     * @return string
     */
    private function getResourceName(string $resource): string
    {
        return trim(str_replace(' ', '', mb_convert_case(str_replace('-', ' ', $resource), MB_CASE_TITLE)));
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return mixed
     */
    private function dispatchToController(Request $request, Response $response, array $args)
    {
        $args['action'] = strtolower($request->getMethod());
        $controller = $this->container->get($this->controllerClass);

        return $controller->__invoke($request, $response, $args);
    }
}

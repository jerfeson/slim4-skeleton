<?php

namespace App\Middleware;

use App\Enum\HttpStatusCode;
use App\Helpers\Payload\Payload;
use App\Message\Message;
use League\OAuth2\Server\Exception\OAuthServerException;
use Lib\Utils\Session;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

/**
 * Class OAuth.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 3.0.0
 *
 * @deprecated
 */
class OAuth
{
    /**
     * @var array
     */
    private $noOAuthRoutes = [
        '/',
        'authentication/authentication/token',
        'authentication/authentication/login',
        'authentication/authentication/logout',
        'payment/payment/return',
    ];

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandler         $handler
     *
     * @return mixed
     */
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        if ($route && count($route->getArguments())) {
            $routeAccessed = $this->buildRouteAccessed($request, $route);
            $session = Session::get('user');
            $token = isset($session['token']) ? $session['token'] : $request->getHeader('Authorization');

            //Verify if route can be accessed without token
            if (
                in_array($routeAccessed, $this->noOAuthRoutes)
                || !isset($route->getArguments()['module'])
            ) {
                return $handler->handle($request);
            }

            try {
                if (!$token) {
                    Session::destroy();
                }

                if (!$session && !$token) {
                    throw new \Exception(Message::ACCESS_DENIED);
                }

                // if the session is from the application, then put the token in the header
                if (!is_array($token)) {
                    $request = $request->withAddedHeader('Authorization', "Bearer {$token[0]}");
                }

                $request = \App\Helpers\OAuth::validateBearer($request);

                return $handler->handle($request);
            } catch (OAuthServerException $exception) {
                $payload = new Payload(
                    HttpStatusCode::UNAUTHORIZED,
                    [
                        'message' => $exception->getHint(),
                    ]
                );

                return $this->exceptionResponse($route, $payload);
            } catch (\Exception $exception) {
                $payload = new Payload(
                    HttpStatusCode::BAD_REQUEST,
                    [
                        'message' => $exception->getMessage(),
                    ]
                );

                return $this->exceptionResponse($route, $payload);
            }
        }

        return $handler->handle($request);
    }

    /**
     * @param $request
     * @param $route
     *
     * @return string
     */
    private function buildRouteAccessed($request, $route)
    {
        $class = isset($route->getArguments()['class']);
        $method = isset($route->getArguments()['method']);
        $idOrClass = isset($route->getArguments()['idOrClass']);
        $id = isset($route->getArguments()['id']);
        if ($this->isApi($route)) {
            if ($class) {
                $class = $route->getArguments()['class'] . '/';
            } else {
                $class = $route->getArguments()['module'] . '/';
            }

            if ($idOrClass) {
                $method = $route->getArguments()['idOrClass'];
            } elseif ($method) {
                $method = $route->getArguments()['method'];
            } else {
                $method = strtolower($request->getMethod()) . 'Action';
            }
        }

        $module = isset($route->getArguments()['module']) ? $route->getArguments()['module'] . '/' : '';

        return $module . $class . $method;
    }

    /**
     * @param $route
     *
     * @return bool
     */
    private function isApi($route)
    {
        return explode('/', $route->getPattern())[1] === 'api';
    }

    /**
     * @param $route
     * @param $payload
     *
     * @return mixed
     */
    private function exceptionResponse($route, $payload)
    {
        $response = new \Slim\Psr7\Response();

        if ($this->isApi($route)) {
            $data = json_encode($payload, JSON_PRETTY_PRINT);
            $response->getBody()->write($data);

            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus($payload->getStatusCode())
            ;
        }
    }
}

<?php


namespace App\Middleware;

use App\Message\Message;
use League\OAuth2\Server\Exception\OAuthServerException;
use Lib\Utils\Session;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

/**
 * Class OAuth
 *
 * @package App\Middleware
 *
 * @author Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since 1.1.0
 *
 * @version 1.1.0
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
        'authentication/authentication/logout'
    ];

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandler $handler
     *
     * @return mixed
     */
    public function __invoke(Request $request, RequestHandler $handler)
    {

        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        if ($route && count($route->getArguments())) {

            $module = isset($route->getArguments()['module']) ? $route->getArguments()['module'] . '/' : '';
            $routeAccessed = $module . $route->getArguments()['class'] . '/' . $route->getArguments()['method'];
            $session = Session::get('user');

            //Verify if route can be accessed without token
            if (in_array($routeAccessed, $this->noOAuthRoutes) ||
                !isset($route->getArguments()['module'])) {
                return $handler->handle($request);
            }

            try {
                //todo make it better
                if (!$session) {
                    throw new \Exception(Message::ACCESS_DENIED);
                }

                //Your Token Validation Business Rule
                $token = isset($session['token']) ? $session['token'] : null;

                if (!$token) {
                    Session::destroy();
                }

                $request = $request->withAddedHeader('Authorization', "Bearer {$token}");
                $request = \App\Helpers\OAuth::validateBearer($request);

                return $handler->handle($request);
            } catch (OAuthServerException $exception) {
                Session::destroy();
                $response = new \Slim\Psr7\Response();
                return $response->withHeader('Location',
                    '/'
                )->withStatus(302);
            } catch (\Exception $exception) {
                $response = new \Slim\Psr7\Response();
                return $response->withHeader('Location',
                    '/'
                )->withStatus(302);
            }
        }

        return $handler->handle($request);
    }
}
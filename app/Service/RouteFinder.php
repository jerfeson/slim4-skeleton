<?php

namespace App\Service;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteInterface;
use Slim\Interfaces\RouteResolverInterface;
use Slim\Routing\RoutingResults;

/**
 * Class RouteFinder.
 *
 * todo move to another location
 *
 * @author  Thiago Daher
 */
class RouteFinder
{
    /**
     * @var \Slim\Interfaces\RouteResolverInterface
     */
    private RouteResolverInterface $routeResolver;

    /**
     * @var \Slim\Interfaces\RouteCollectorInterface
     */
    private RouteCollectorInterface $routeCollector;

    /**
     * @param \Slim\Interfaces\RouteResolverInterface  $routeResolver
     * @param \Slim\Interfaces\RouteCollectorInterface $routeCollector
     */
    public function __construct(RouteResolverInterface $routeResolver, RouteCollectorInterface $routeCollector)
    {
        $this->routeResolver = $routeResolver;
        $this->routeCollector = $routeCollector;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return null|\Slim\Interfaces\RouteInterface
     */
    public function getRouteFromRequest(ServerRequestInterface $request): ?RouteInterface
    {
        $result = $this->routeResolver->computeRoutingResults(
            $request->getUri()->getPath(),
            $request->getMethod()
        );

        if ($result->getRouteStatus() !== RoutingResults::FOUND) {
            return null;
        }

        $routes = $this->routeCollector->getRoutes();

        return $routes[$result->getRouteIdentifier()] ?? null;
    }
}

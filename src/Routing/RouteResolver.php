<?php

namespace Bence\Routing;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RouteResolver
 *
 * @author Bence Borbély
 */
class RouteResolver
{

    /**
     * @param ServerRequestInterface $request
     * @return Route
     */
    public function resolve(ServerRequestInterface $request)
    {
        $queryParams = $request->getQueryParams();

        if (!isset($queryParams['route'])) {
            return new Route();
        }

        $task = isset($queryParams['task']) ? $queryParams['task'] : 'index';

        $controllerClass = 'Bence\\Controller\\'. ucfirst($queryParams['route']) . 'Controller';
        $method = $task . 'Action';

        $route = new Route();
        $route->setParameter($queryParams['route']);
        $route->setControllerClass($controllerClass);
        $route->setTask($task);
        $route->setActionMethod($method);

        return $route;
    }

}

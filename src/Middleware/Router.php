<?php

namespace Bence\Middleware;

use Bence\Routing\RouteResolver;
use Bence\Session\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Router
 *
 * @author Bence Borbély
 */
class Router implements Runnable
{

    /**
     * @var RouteAwareRunnable
     */
    protected $next;

    /**
     * @var RouteResolver
     */
    protected $routeResolver;

    /**
     * @param RouteAwareRunnable $next
     * @param RouteResolver $routeResolver
     */
    public function __construct(RouteAwareRunnable $next, RouteResolver $routeResolver)
    {
        $this->next = $next;
        $this->routeResolver = $routeResolver;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param Session $session
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request, ResponseInterface $response, Session $session)
    {
        $route = $this->routeResolver->resolve($request);

        if (!method_exists($route->getControllerClass(), $route->getActionMethod())) {
            return $this->getRedirectResponse($request, $response, 'home');
        }

        $this->next->setRoute($route);

        return $this->next->run($request, $response, $session);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $routeParam
     * @return ResponseInterface|static
     */
    protected function getRedirectResponse(ServerRequestInterface $request, ResponseInterface $response, $routeParam)
    {
        $uri = $request->getUri()->withQuery('route=' . $routeParam);
        $response = $response
            ->withStatus(301, 'Moved Permanently')
            ->withHeader('Location', (string) $uri);

        return $response;
    }

}

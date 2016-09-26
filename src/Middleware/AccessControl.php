<?php

namespace Bence\Middleware;

use Bence\Model\User\User;
use Bence\Session\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Bence\Routing\Route;

/**
 * Class AccessControl
 *
 * @author Bence Borbély
 */
class AccessControl implements RouteUserAwareRunnable
{

    /**
     * @var RouteUserAwareRunnable
     */
    protected $next;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param RouteUserAwareRunnable $next
     */
    public function __construct(RouteUserAwareRunnable $next)
    {
        $this->next = $next;
    }

    /**
     * @param Route $route
     */
    public function setRoute(Route $route)
    {
        $this->route = $route;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param Session $session
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request, ResponseInterface $response, Session $session)
    {
        $permissions = $this
            ->user
            ->getUserGroup()
            ->getPermissions();

        $common = [
            'home',
        ];

        if (!in_array($this->route->getParameter(), array_merge($permissions, $common))) {
            return $this->getRedirectResponse($request, $response, 'home');
        }

        $this->next->setUser($this->user);
        $this->next->setRoute($this->route);
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

<?php

namespace Bence\Middleware;

use Bence\ControllerFactory;
use Bence\Session\Session;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Bence\Routing\Route;
use Bence\Model\User\User;
use Bence\Template\TemplateInterface;
use Bence\Controller\AbstractController;

/**
 * Class Application
 *
 * @author Bence Borbély
 */
class Application implements RouteUserAwareRunnable
{

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var TemplateInterface
     */
    protected $template;

    /**
     * @var ControllerFactory
     */
    protected $controllerFactory;

    /**
     * @param TemplateInterface $template
     * @param ControllerFactory $controllerFactory
     */
    public function __construct(TemplateInterface $template, ControllerFactory $controllerFactory)
    {
        $this->template = $template;
        $this->controllerFactory = $controllerFactory;
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
     * @return ResponseInterface $response
     */
    public function run(ServerRequestInterface $request, ResponseInterface $response, Session $session)
    {
        /**
         * @var AbstractController $controller
         */
        $controller = $this->controllerFactory->create($this->route->getControllerClass());

        $controller->setRequest($request);
        $controller->setResponse($response);
        $controller->setSession($session);
        $controller->setTemplate($this->template);
        $controller->setUser($this->user);

        $action = $this->route->getActionMethod();

        $controller->init();
        $response = $controller->$action();

        return $response;
    }

}

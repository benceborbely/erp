<?php

namespace Bence\Middleware;

use Bence\Service\LoginService;
use Bence\Session\Session;
use Bence\Template\TemplateInterface;
use Gregwar\Captcha\CaptchaBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Bence\Routing\Route;

/**
 * Class Authentication
 *
 * @author Bence Borbély
 */
class Authentication implements RouteAwareRunnable
{

    /**
     * @var RouteAwareRunnable
     */
    protected $next;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var LoginService
     */
    protected $loginService;

    /**
     * @var TemplateInterface
     */
    protected $template;

    /**
     * @var CaptchaBuilder
     */
    protected $captchaBuilder;

    /**
     * @param RouteUserAwareRunnable $next
     * @param LoginService $loginService
     * @param TemplateInterface $template
     * @param CaptchaBuilder $captchaBuilder
     */
    public function __construct(
        RouteUserAwareRunnable $next,
        LoginService $loginService,
        TemplateInterface $template,
        CaptchaBuilder $captchaBuilder)
    {
        $this->next = $next;
        $this->loginService = $loginService;
        $this->template = $template;
        $this->captchaBuilder = $captchaBuilder;
    }

    /**
     * @param Route $route
     */
    public function setRoute(Route $route)
    {
        $this->route = $route;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param Session $session
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request, ResponseInterface $response, Session $session)
    {
        $queryParams = $request->getQueryParams();
        $data = [];

        //Handling logout request
        if (isset($queryParams['logout'])) {
            $this->loginService->logout();

            $this->captchaBuilder->build();
            $session->set('code', $this->captchaBuilder->getPhrase());
            $data['img'] = $this->captchaBuilder->inline();

            return $this->loginResponse($response, $data);
        }

        if ($this->loginService->isLoggedIn()) {
            $user = $this->loginService->getUser();
            $this->next->setUser($user);
            $this->next->setRoute($this->route);
            return $this->next->run($request, $response, $session);
        }

        //Handling login request
        if ($request->getMethod() == 'POST') {
            $post = $request->getParsedBody();
            $username = $post['username'];
            $password = $post['password'];
            $code = $post['code'];

            if ($code == $session->get('code')
                && $username != ""
                && $password != ""
                && $this->loginService->login($username, $password)) {
                $session->remove('code');
                $this->next->setRoute($this->route);
                $this->next->setUser($this->loginService->getUser());
                return $this->next->run($request, $response, $session);
            }

            $data['msg'] = 'Unsuccessful login. Please try again!';
        }

        $this->captchaBuilder->build();
        $session->set('code', $this->captchaBuilder->getPhrase());
        $data['img'] = $this->captchaBuilder->inline();

        return $this->loginResponse($response, $data);
    }

    /**
     * @param ResponseInterface $response
     * @param array $data
     * @return ResponseInterface|static
     */
    protected function loginResponse(ResponseInterface $response, array $data = array())
    {
        $content = $this->template->render('login.tpl', $data);
        $body = $response->getBody();
        $body->write($content);
        return $response->withBody($body);
    }

}

<?php

namespace Bence\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Bence\Session\Session;
use Bence\Template\TemplateInterface;
use Bence\Model\User\User;

/**
 * Class Controller
 *
 * @author Bence Borbély
 */
abstract class AbstractController
{

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var TemplateInterface
     */
    protected $template;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param ServerRequestInterface $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @param ResponseInterface $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param TemplateInterface $template
     */
    public function setTemplate(TemplateInterface $template)
    {
        $this->template = $template;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $template
     * @return ResponseInterface
     */
    protected function render($template)
    {
        $body = $this->response->getBody();

        $body->write($this->template->render($template, $this->data));
        $this->response = $this->response->withBody($body);

        return $this->response;
    }

    public final function init()
    {
        //Add permissions to build menu
        $this->data['permissions'] = $this
            ->user
            ->getUserGroup()
            ->getPermissions();
    }

    /**
     * @return ResponseInterface|static
     */
    public abstract function indexAction();

}

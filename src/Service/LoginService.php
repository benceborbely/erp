<?php

namespace Bence\Service;

use Bence\Session\Session;
use Bence\Model\User\UserProvider;
use Bence\Model\User\User;

/**
 * Class LoginService
 *
 * @author Bence Borbély
 */
class LoginService
{

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var UserProvider
     */
    protected $userProvider;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $id;

    /**
     * @param Session $session
     * @param UserProvider $userProvider
     */
    public function __construct(Session $session, UserProvider $userProvider)
    {
        $this->session = $session;
        $this->userProvider = $userProvider;

        if ($this->isLoggedIn()) {
            $this->user = $this->userProvider->getUserByUsername($session->get('user'));
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login($username, $password)
    {
        $this->user = $this->userProvider->getUserByUsername($username);

        if ($this->user === null
            || $this->user->getPassword() !== md5($password)) {
            return false;
        }

        $this->session->set('user', $this->user->getUsername());
        return true;
    }

    /**
     * @return int
     */
    public function isLoggedIn()
    {
        return $this->session->get('user', '');
    }

    /**
     *
     */
    public function logout()
    {
        $this->session->remove('user');
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

}

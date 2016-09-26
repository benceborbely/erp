<?php

namespace Bence\Tests\Service;

use Bence\Service\LoginService;

/**
 * Class LoginServiceTest
 *
 * @author Bence Borbély
 */
class LoginServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Bence\Service\LoginService::login
     */
    public function testSuccessfulLogin()
    {
        $username = 'admin';
        $password ='admin';
        $passwordHash = md5($password);

        $user = $this->getUserMock();

        $user
            ->expects($this->at(0))
            ->method('getPassword')
            ->will($this->returnValue($passwordHash));

        $user
            ->expects($this->at(1))
            ->method('getUsername')
            ->will($this->returnValue($username));

        $userProvider = $this->getUserProviderMock();

        $userProvider
            ->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->will($this->returnValue($user));

        $session = $this->getSessionMock();

        $session
            ->expects($this->at(0))
            ->method('get')
            ->with('user')
            ->will($this->returnValue(''));

        $session
            ->expects($this->at(1))
            ->method('set')
            ->with('user', $username);

        $loginService = new LoginService($session, $userProvider);

        $this->assertTrue($loginService->login($username, $password));
    }

    /**
     * @covers Bence\Service\LoginService::login
     */
    public function testLoginIfPasswordNotMatch()
    {
        $username = 'admin';
        $password ='admn';
        $passwordHash = md5('admin');

        $user = $this->getUserMock();

        $user
            ->expects($this->once())
            ->method('getPassword')
            ->will($this->returnValue($passwordHash));

        $userProvider = $this->getUserProviderMock();

        $userProvider
            ->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->will($this->returnValue($user));

        $session = $this->getSessionMock();

        $session
            ->expects($this->once())
            ->method('get')
            ->with('user')
            ->will($this->returnValue(''));

        $loginService = new LoginService($session, $userProvider);

        $this->assertFalse($loginService->login($username, $password));
    }

    /**
     * @covers Bence\Service\LoginService::login
     */
    public function testLoginIfUsernameNotMatch()
    {
        $badUsername = 'admn';
        $password ='admin';

        $userProvider = $this->getUserProviderMock();

        $userProvider
            ->expects($this->once())
            ->method('getUserByUsername')
            ->with($badUsername)
            ->will($this->returnValue(null));

        $session = $this->getSessionMock();

        $session
            ->expects($this->once())
            ->method('get')
            ->with('user')
            ->will($this->returnValue(''));

        $loginService = new LoginService($session, $userProvider);

        $this->assertFalse($loginService->login($badUsername, $password));
    }

    /**
     * @covers Bence\Service\LoginService::isLoggedIn
     * @covers Bence\Service\LoginService::getUser
     */
    public function testIsLoggedInIfNotLoggedIn()
    {
        $session = $this->getSessionMock();

        $session
            ->expects($this->any())
            ->method('get')
            ->with('user', '')
            ->will($this->returnValue(false));

        $userProvider = $this->getUserProviderMock();

        $loginService = new LoginService($session ,$userProvider);

        $this->assertFalse($loginService->isLoggedIn());
        $this->assertEquals(null, $loginService->getUser());
    }

    /**
     * @covers Bence\Service\LoginService::isLoggedIn
     * @covers Bence\Service\LoginService::getUser
     */
    public function testIsLoggedInIfLoggedIn()
    {
        $session = $this->getSessionMock();

        $session
            ->expects($this->any())
            ->method('get')
            ->with('user', '')
            ->will($this->returnValue(true));

        $user = $this->getUserMock();

        $userProvider = $this->getUserProviderMock();

        $userProvider
            ->expects($this->once())
            ->method('getUserByUsername')
            ->will($this->returnValue($user));

        $loginService = new LoginService($session ,$userProvider);

        $this->assertTrue($loginService->isLoggedIn());
        $this->assertEquals($user, $loginService->getUser());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getUserMock()
    {
        $user = $this
            ->getMockBuilder('Bence\Model\User\User')
            ->setMethods([
                'getUsername',
                'getPassword',
            ])
            ->getMock();

        return $user;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getUserProviderMock()
    {
        $userProvider = $this
            ->getMockBuilder('Bence\Model\User\UserProvider')
            ->disableOriginalConstructor()
            ->setMethods(['getUserByUsername'])
            ->getMock();

        return $userProvider;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSessionMock()
    {
        $session = $this
            ->getMockBuilder('Bence\Session\Session')
            ->disableOriginalConstructor()
            ->setMethods([
                'get',
                'set',
                'remove',
            ])
            ->getMock();

        return $session;
    }

}

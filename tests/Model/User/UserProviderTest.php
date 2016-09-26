<?php

namespace Bence\Tests\User;

use Bence\Model\User\UserProvider;

/**
 * Class UserProviderTest
 *
 * @author Bence Borbély
 */
class UserProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Bence\Model\User\UserProvider::getUserByUsername
     */
    public function testGetUserByUsernameIfUserExists()
    {
        $database = $this->getDatabaseMock();

        $database
            ->expects($this->once())
            ->method('query');

        $database
            ->expects($this->once())
            ->method('bind');

        $userRecord = [
            'id' => 2,
            'username' => 'hr_user',
            'password' => md5('hr_user'),
        ];

        $userGroupRecord = [
            'id' => 1,
            'name' => 'hr',
        ];

        $hrPermissions = [
            'employee',
            'applicants'
        ];

        $rows = [];
        foreach ($hrPermissions as $route) {
            $rows[] = [
                'ug_id' => $userGroupRecord['id'],
                'name' => $userGroupRecord['name'],
                'id' => $userRecord['id'],
                'username' => $userRecord['username'],
                'password' => $userRecord['password'],
                'route' => $route,
            ];
        }

        $database
            ->expects($this->once())
            ->method('resultSet')
            ->will($this->returnValue($rows));

        $userProvider = new UserProvider($database);

        $result = $userProvider->getUserByUsername($userRecord['username']);

        $this->assertInstanceOf('Bence\Model\User\User', $result);
        $this->assertEquals($userRecord['id'], $result->getId());
        $this->assertEquals($userRecord['username'], $result->getUsername());
        $this->assertEquals($userRecord['password'], $result->getPassword());
        $this->assertInstanceOf('Bence\Model\User\UserGroup', $result->getUserGroup());
        $this->assertEquals($userGroupRecord['id'], $result->getUserGroup()->getId());
        $this->assertEquals($userGroupRecord['name'], $result->getUserGroup()->getName());
        $this->assertEquals($hrPermissions, $result->getUserGroup()->getPermissions());
    }

    /**
     * @covers Bence\Model\User\UserProvider::getUserByUsername
     */
    public function testGetByUsernameIfUserNotExists()
    {
        $database = $this->getDatabaseMock();

        $database
            ->expects($this->once())
            ->method('query');

        $database
            ->expects($this->once())
            ->method('bind');

        $database
            ->expects($this->once())
            ->method('resultSet')
            ->will($this->returnValue([]));

        $userProvider = new UserProvider($database);

        $this->assertEquals(null, $userProvider->getUserByUsername('asd'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getDatabaseMock()
    {
        $database = $this
            ->getMockBuilder('Bence\Database\Database')
            ->disableOriginalConstructor()
            ->setMethods([
                'query',
                'bind',
                'resultSet',
            ])
            ->getMock();

        return $database;
    }

}

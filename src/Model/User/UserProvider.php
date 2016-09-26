<?php

namespace Bence\Model\User;

use Bence\Database\Database;

/**
 * Class UserProvider
 *
 * @author Bence Borbély
 */
class UserProvider
{

    /**
     * @var Database
     */
    protected $database;

    /**
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param string $username
     * @return User|null
     */
    public function getUserByUsername($username)
    {
        $this->database->query('SELECT `routes`.`route`, `user_groups`.`name`, `user_groups`.`id` as ug_id, `users`.*
                                FROM `routes`
                                INNER JOIN `permissions` ON `permissions`.`route_id` = `routes`.`id`
                                INNER JOIN `user_groups` ON `user_groups`.`id` = `permissions`.`user_group_id`
                                INNER JOIN `users` ON `users`.`user_group_id` = `user_groups`.`id`
                                WHERE `users`.`username` = :parameter');
        $this->database->bind(':parameter', $username);

        $rows = $this->database->resultSet();

        if (!$rows) {
            return null;
        }

        $userGroup = new UserGroup();
        foreach ($rows as $row) {
            $userGroup->addPermission($row['route']);
        }

        $userGroup->setName($rows[0]['name']);
        $userGroup->setId($rows[0]['ug_id']);

        $user = new User();
        $user->setId($rows[0]['id']);
        $user->setUsername($rows[0]['username']);
        $user->setPassword($rows[0]['password']);
        $user->setUserGroup($userGroup);

        return $user;
    }

}

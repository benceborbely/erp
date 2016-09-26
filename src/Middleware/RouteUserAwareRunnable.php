<?php

namespace Bence\Middleware;

use Bence\Model\User\User;

/**
 * Interface RouteUserAwareRunnable
 *
 * @author Bence Borbély
 */
interface RouteUserAwareRunnable extends RouteAwareRunnable
{

    public function setUser(User $user);

}

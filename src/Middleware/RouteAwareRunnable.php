<?php

namespace Bence\Middleware;

use Bence\Routing\Route;

/**
 * Interface RouteAwareRunnable
 *
 * @author Bence Borbély
 */
interface RouteAwareRunnable extends Runnable
{

    public function setRoute(Route $route);

}

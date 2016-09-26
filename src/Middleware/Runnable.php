<?php

namespace Bence\Middleware;

use Bence\Session\Session;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface Runnable
 *
 * @author Bence Borbély
 */
interface Runnable
{

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param Session $session
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request, ResponseInterface $response, Session $session);

}

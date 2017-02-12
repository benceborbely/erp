<?php

namespace Bence;

use Bence\Exception\ControllerClassNotFoundException;
use Bence\Controller\AbstractController;
use Bence\Exception\NotAControllerException;

/**
 * Class ControllerFactory
 *
 * @author Bence Borbély
 */
class ControllerFactory
{
    /**
     * @param string $class
     * @return AbstractController
     * @throws ControllerClassNotFoundException
     * @throws NotAControllerException
     */
    public function create($class)
    {
        if (!class_exists($class)) {
            throw new ControllerClassNotFoundException($class);
        }

        $controller = new $class();

        if (!is_subclass_of($class, 'Bence\Controller\AbstractController')) {
            throw new NotAControllerException($class);
        }

        return $controller;
    }
}

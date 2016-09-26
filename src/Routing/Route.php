<?php

namespace Bence\Routing;

/**
 * Class Route
 *
 * @author Bence Borbély
 */
class Route
{

    /**
     * @var string
     */
    protected $parameter;

    /**
     * @var string
     */
    protected $controllerClass;

    /**
     * @var string
     */
    protected $task;

    /**
     * @var string
     */
    protected $actionMethod;

    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param string $parameter
     * @return $this
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    /**
     * @return string
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param string $task
     * @return $this
     */
    public function setTask($task)
    {
        $this->task = $task;
        return $this;
    }

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return $this->controllerClass;
    }

    /**
     * @param string $controllerClass
     * @return $this
     */
    public function setControllerClass($controllerClass)
    {
        $this->controllerClass = $controllerClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getActionMethod()
    {
        return $this->actionMethod;
    }

    /**
     * @param string $actionMethod
     * @return $this
     */
    public function setActionMethod($actionMethod)
    {
        $this->actionMethod = $actionMethod;
        return $this;
    }

}

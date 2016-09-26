<?php

namespace Bence\Exception;

/**
 * Class ControllerClassNotFoundException
 *
 * @author Bence Borbély
 */
class ControllerClassNotFoundException extends \Exception
{

    /**
     * @var string
     */
    protected $class;

    /**
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;

        $this->message = 'Controller class - ' . $this->class .  ' - not found!';
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

}

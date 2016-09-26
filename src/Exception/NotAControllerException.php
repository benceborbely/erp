<?php

namespace Bence\Exception;

/**
 * Class NotAControllerException
 *
 * @author Bence Borbély
 */
class NotAControllerException extends \Exception
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

        $this->message = 'Class - ' . $this->class .  ' - is not a Controller';
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

}

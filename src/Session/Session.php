<?php

namespace Bence\Session;

/**
 * Class Session
 *
 * @author Bence Borbély
 */
class Session
{

    /**
     * @var bool
     */
    protected $started = false;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var \SessionHandlerInterface
     */
    protected $handler;

    /**
     * @param \SessionHandlerInterface $sessionHandler
     */
    public function __construct(\SessionHandlerInterface $sessionHandler)
    {
        $this->handler = $sessionHandler;
    }

    /**
     *
     */
    public function start()
    {
        if (!$this->started) {
            session_set_save_handler($this->handler);

            session_start();
            $this->started = true;

            $this->load();
        }
    }

    /**
     *
     */
    protected function load()
    {
        $this->data = &$_SESSION;
    }

    /**
     * @param $key
     * @param null $default
     * @return null
     */
    public function get($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @param $key
     */
    public function remove($key)
    {
        unset($this->data[$key]);
    }

}

<?php

namespace Bence\ErrorHandler;

use Bence\ErrorHandler\Action\ActionInterface;

/**
 * Class ErrorHandler
 *
 * @author Bence Borbély
 */
class ErrorHandler
{

    /**
     * @var Error[]
     */
    protected $errors = [];

    /**
     * @var ActionInterface[]
     */
    protected $actions = [];

    /**
     * @var ErrorMessageFormatterInterface
     */
    protected $errorMessageFormatter;

    /**
     * @param ErrorMessageFormatterInterface $errorMessageFormatter
     */
    public function __construct(ErrorMessageFormatterInterface $errorMessageFormatter)
    {
        $this->errorMessageFormatter = $errorMessageFormatter;
    }

    /**
     * @param ActionInterface $action
     */
    public function addAction(ActionInterface $action)
    {
        $this->actions[] = $action;
    }

    /**
     *
     */
    public function register()
    {
        set_error_handler([$this, 'stashError']);
        set_exception_handler([$this, 'stashException']);
        register_shutdown_function([$this, 'shutdown']);
    }

    /**
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     */
    public function stashError($errno, $errstr, $errfile, $errline)
    {
        $error = new Error();
        $error->setType($this->getName($errno));
        $error->setCode($errno);
        $error->setFile($errfile);
        $error->setLine($errline);
        $error->setMessage($errstr);

        $this->errors[] = $error;

        switch ($errno) {
            case E_ERROR:
            case E_USER_ERROR:
                exit('Sorry but some error happened, please contact our customer service!');
        }
    }

    /**
     * @param $errorCode
     * @return string
     */
    protected function getName($errorCode)
    {
        switch ($errorCode) {
            case E_ERROR:
                return 'FATAL ERROR';
            case E_RECOVERABLE_ERROR:
                return 'RECOVERABLE ERROR';
            case E_WARNING:
                return 'WARNING';
            case E_PARSE:
                return 'PARSE ERROR';
            case E_NOTICE:
                return 'NOTICE';
            case E_STRICT:
                return 'STRICT ERROR';
            case E_DEPRECATED:
                return 'DEPRECATED';
            case E_USER_DEPRECATED:
                return 'USER DEPRECATED';
            case E_USER_NOTICE:
                return 'USER NOTICE';
            case E_USER_ERROR:
                return 'FATAL USER ERROR';
            case E_USER_WARNING:
                return 'USER WARNING';
            default:
                return 'UNKNOWN ERROR TYPE';
        }
    }

    /**
     * @param \Exception $exception
     */
    public function stashException($exception)
    {
        $error = new Error();
        $error->setType(get_class($exception));
        $error->setCode($exception->getCode());
        $error->setFile($exception->getFile());
        $error->setLine($exception->getLine());
        $error->setMessage($exception->getMessage());

        $this->errors[] = $error;

        exit('Sorry but some error happened, please contact our customer service!');
    }

    /**
     *
     */
    public function shutdown()
    {
        $msg = "";

        foreach ($this->errors as $error) {
            $msg .= $this->errorMessageFormatter->format($error) . "    \n";
        }

        foreach ($this->actions as $action) {
            $action->run($msg);
        }
    }

}

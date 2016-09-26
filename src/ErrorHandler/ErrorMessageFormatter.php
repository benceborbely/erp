<?php

namespace Bence\ErrorHandler;

/**
 * Class ErrorMessageFormatter
 *
 * @author Bence Borbély
 */
class ErrorMessageFormatter implements ErrorMessageFormatterInterface
{

    /**
     * @param Error $error
     * @return string
     */
    public function format(Error $error)
    {
        $msg = $error->getType() . " - Code: " . $error->getCode() . "\n"
            . "Message: " . $error->getMessage() . "\n"
            . "File: " . $error->getFile() . "\n"
            . "Line: " . $error->getLine() . "\n";

        return $msg;
    }

}

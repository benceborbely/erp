<?php

namespace Bence\ErrorHandler;

/**
 * Interface ErrorMessageFormatterInterface
 *
 * @author Bence Borbély
 */
interface ErrorMessageFormatterInterface
{

    /**
     * @param Error $error
     * @return string
     */
    public function format(Error $error);

}

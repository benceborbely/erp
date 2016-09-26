<?php

namespace Bence\ErrorHandler\Action;

/**
 * Interface ActionInterface
 *
 * @author Bence Borbély
 */
interface ActionInterface
{

    /**
     * @param string $msg
     * @return void
     */
    public function run($msg);

}

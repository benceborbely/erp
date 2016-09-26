<?php

namespace Bence\Controller;

/**
 * Class EmployeeController
 *
 * @author Bence Borbély
 */
class EmployeeController extends AbstractController
{

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction()
    {
        //TODO collecting employees

        return $this->render('employee_list.tpl', array());
    }

}

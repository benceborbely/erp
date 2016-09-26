<?php

namespace Bence\Controller;

/**
 * Class OrderController
 *
 * @author Bence Borbély
 */
class OrderController extends AbstractController
{

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction()
    {
        //TODO collecting orders

        return $this->render('order_list.tpl', array());
    }

}

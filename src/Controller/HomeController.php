<?php

namespace Bence\Controller;

/**
 * Class HomeController
 *
 * @author Bence Borbély
 */
class HomeController extends AbstractController
{

    public function indexAction()
    {
        $data = [
            'permissions' => $this->user->getUserGroup()->getPermissions(),
        ];

        return $this->render('home.tpl', $data);
    }

}

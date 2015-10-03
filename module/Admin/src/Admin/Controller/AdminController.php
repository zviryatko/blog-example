<?php

namespace Admin\Controller;

use Application\Controller\AbstractController;
use Zend\View\Model\ViewModel;

class AdminController extends AbstractController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function loginAction()
    {
        $this->layout('layout/admin-login');
        return new ViewModel();
    }

    public function registerAction()
    {
        $this->layout('layout/admin-login');
        return new ViewModel();
    }
}


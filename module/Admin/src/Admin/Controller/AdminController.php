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
}


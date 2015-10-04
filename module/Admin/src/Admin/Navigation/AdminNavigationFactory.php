<?php
/**
 * @file
 *
 */

namespace Admin\Navigation;

use Admin\Navigation\AdminNavigation as Navigation;
use Zend\Mvc\Router\RouteInterface;
use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminNavigationFactory extends AbstractNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'admin';
    }
}
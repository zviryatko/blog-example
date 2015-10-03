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

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\Navigation\Navigation
     */
//    public function createService(ServiceLocatorInterface $serviceLocator)
//    {
//        /** @var RouteInterface $router */
//        $router = $serviceLocator->get('Router');
//        return new Navigation($router);
//    }
}
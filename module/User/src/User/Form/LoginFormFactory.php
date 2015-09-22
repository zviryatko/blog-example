<?php
/**
 * @file
 *
 */

namespace User\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginFormFactory implements FactoryInterface
{

    /**
     * @inheritdoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Form factory provide FormElementManager.
        if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        /** @var \Zend\Authentication\AuthenticationService $authService */
        $authService = $serviceLocator->get('Zend\Authentication\AuthenticationService');

        return new LoginForm($authService);
    }
}

<?php
/**
 * @file
 *
 */

namespace User\Controller;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Form factory provide FormElementManager.
        if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        /** @var EntityManager $objectManager */
        $objectManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $objectRepository = $objectManager->getRepository('User\Entity\User');

        return new AdminController($objectRepository);
    }
}

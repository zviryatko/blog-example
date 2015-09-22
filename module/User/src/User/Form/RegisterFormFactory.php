<?php
/**
 * @file
 *
 */

namespace User\Form;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DoctrineORMModule\Service\DoctrineObjectHydratorFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Stdlib\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

class RegisterFormFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        /** @var HydratorPluginManager $hydratorManager */
        $hydratorManager = $serviceLocator->get('HydratorManager');
        /** @var DoctrineObject $hydrator */
        $hydrator = $hydratorManager->get('DoctrineModule\Stdlib\Hydrator\DoctrineObject');
        $hydrator->setNamingStrategy(new UnderscoreNamingStrategy());

        /** @var EntityManager $objectManager */
        $objectManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $objectRepository = $objectManager->getRepository('User\Entity\User');

        return new RegisterForm($objectRepository, $hydrator);
    }
}

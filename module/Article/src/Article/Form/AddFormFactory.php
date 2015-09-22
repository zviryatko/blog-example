<?php
/**
 * @file
 *
 */

namespace Article\Form;

use Application\Entity\File;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Stdlib\Hydrator\NamingStrategy\UnderscoreNamingStrategy;
use Zend\Stdlib\Hydrator\Strategy\ClosureStrategy;

class AddFormFactory implements FactoryInterface
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
        if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        /** @var HydratorPluginManager $hydratorManager */
        $hydratorManager = $serviceLocator->get('HydratorManager');
        /** @var DoctrineObject $hydrator */
        $hydrator = $hydratorManager->get('DoctrineModule\Stdlib\Hydrator\DoctrineObject');
        $hydrator->setNamingStrategy(new UnderscoreNamingStrategy());
        $hydrator->addStrategy(
            'thumbnail', new ClosureStrategy(
                function (File $file = null) {
                    if ($file) {
                        return $file->getFilename();
                    }
                },
                function ($value) {
                    if (!empty($value['tmp_name'])) {
                        $value['filename'] = basename($value['tmp_name']);
                        return $value;
                    }
                }
            )
        );

        /** @var EntityManager $objectManager */
        $objectManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        return new AddForm($objectManager, $hydrator);
    }
}
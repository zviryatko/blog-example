<?php
/**
 * @file
 *
 */

namespace Application\Router;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Router\Http\Segment;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Mvc\Router\RoutePluginManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Router\Exception;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface as Request;

class EntityRouter extends Segment implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    public function match(Request $request, $pathOffset = null, array $options = array())
    {
        $routeMatch = parent::match($request, $pathOffset, $options);

        if ($routeMatch instanceof RouteMatch) {
            $entityName = $routeMatch->getParam('entity');
            $params = $routeMatch->getParams();
            unset($params['entity'], $params['controller'], $params['action']);
            /** @var EntityManager $objectManager */
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $objectRepository = $objectManager->getRepository($entityName);
            $entity = $objectRepository->findOneBy($params);
            if (!$entity) {
                return null;
            }
            $routeMatch->setParam('entity', $entity);
        }

        return $routeMatch;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return self
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof RoutePluginManager) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
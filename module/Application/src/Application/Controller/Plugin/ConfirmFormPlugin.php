<?php
/**
 * @file
 *
 */

namespace Application\Controller\Plugin;


use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConfirmFormPlugin extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    public function __invoke($message, $confirm, $decline)
    {
        /** @var \Application\Form\ConfirmForm $confirmForm */
        $confirmForm = $this->getServiceLocator()->get('FormElementManager')->get('Application\Form\ConfirmForm');
        $confirmForm->setOption('message', $message);
        $confirmForm->get('confirm')->setAttribute('value', $confirm);
        $confirmForm->get('decline')->setAttribute('value', $decline);

        return $confirmForm;
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
        if ($serviceLocator instanceof PluginManager) {
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
<?php
/**
 * @file
 *
 */

namespace Application\View\Helper;


use Zend\Form\View\Helper\FormRow;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FormRowFactory implements FactoryInterface
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
        $helper = new FormRow();
        $helper->setPartial('basic/form-row.phtml');
        return $helper;
    }
}
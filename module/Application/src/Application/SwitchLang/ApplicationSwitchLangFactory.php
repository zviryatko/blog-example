<?php

namespace Application\SwitchLang;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApplicationSwitchLangFactory implements FactoryInterface
{
  public function createService(ServiceLocatorInterface $serviceLocator)
  {
    $navigation = new ApplicationSwitchLang();
    return $navigation->createService($serviceLocator);
  }
}
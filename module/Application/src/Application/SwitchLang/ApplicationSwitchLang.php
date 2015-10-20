<?php
namespace Application\SwitchLang;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\Session\Container;

class ApplicationSwitchLang extends DefaultNavigationFactory
{
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        $lang_switcher = array();

        if (null === $this->pages) {

            $session = new Container('language');
            $config = $serviceLocator->get('Config');
            $avilable_languages = $config['translator']['locale']['available'];
            $current_lang = $session->language;

            foreach ($avilable_languages as $key => $value) {
              if ($current_lang == $key) {
                $classes = 'item active';
              }
              else  {
                $classes = 'item';
              }
              $lang_switcher[] = array (
                'label' => $value,
                'uri'   => '?language=' . $key,
                'class' => $classes,
              );
            }

            $mvcEvent = $serviceLocator->get('Application')
                      ->getMvcEvent();

            $routeMatch = $mvcEvent->getRouteMatch();
            $router     = $mvcEvent->getRouter();
            $pages      = $this->getPagesFromConfig($lang_switcher);

            $this->pages = $this->injectComponents(
                $pages,
                $routeMatch,
                $router
            );
        }

        return $this->pages;
    }
}
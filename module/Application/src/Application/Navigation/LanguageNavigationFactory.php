<?php

namespace Application\Navigation;

use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Exception;
use Zend\Session\Container;

class LanguageNavigationFactory extends DefaultNavigationFactory
{
    /**
     * @inheritdoc
     */
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {
            $session = new Container('language');

            $configuration = $serviceLocator->get('Config');
            if (!isset($configuration['translator']['available'])) {
                throw new Exception\InvalidArgumentException('Could not find available languages translator configuration key');
            }

            $languages = array();
            foreach ($configuration['translator']['available'] as $key => $value) {
                $classes = ['item'];
                if ($session->language === $key) {
                    $classes[] = 'active';
                }

                $languages[] = array(
                    'label' => $value,
                    'uri' => '?language=' . $key,
                    'class' => implode(' ', $classes),
                );
            }

            $pages = $this->getPagesFromConfig($languages);
            $this->pages = $this->preparePages($serviceLocator, $pages);
        }

        return $this->pages;
    }
}
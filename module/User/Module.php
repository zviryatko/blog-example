<?php
namespace User;

use Zend\Console\Console;
use Zend\EventManager\EventInterface;

class Module
{
    public function onBootstrap(EventInterface $event)
    {
        if (Console::isConsole()) {
            /* @var $app \Zend\Mvc\ApplicationInterface */
            $app = $event->getTarget();
            /* @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
            $serviceManager = $app->getServiceManager();
            $guards = $serviceManager->get('BjyAuthorize\Guards');
            foreach ($guards as $guard) {
                $app->getEventManager()->detach($guard);
            }
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}

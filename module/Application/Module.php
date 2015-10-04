<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Http\Request;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Navigation\View\HelperConfig;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Helper\Navigation\PluginManager;
use Zend\View\Helper\Placeholder\Container\AbstractContainer;
use Zend\View\HelperPluginManager;

class Module
{
    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $serviceManager = $app->getServiceManager();
        $eventManager = $app->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'));
        $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'));

        $routePluginManager = $serviceManager->get('RoutePluginManager');
        $config = $serviceManager->get('Config');
        $config['router']['route_plugins'] = $routePluginManager;
    }

    public function onRoute(MvcEvent $event)
    {
        $route = $event->getRouteMatch();
        $request = $event->getRequest();
        if ($request instanceof Request) {
            // Set default route pagination and order params.
            $route->setParam('page', (int)$request->getQuery('page', $route->getParam('page', 1)));
            $route->setParam('length', (int)$request->getQuery('length', $route->getParam('length', 10)));
            $route->setParam('order_by', $request->getQuery('order_by', $route->getParam('order_by')));
            $route->setParam('order', $request->getQuery('order', $route->getParam('order', 'ASC')));
            // Clean up dynamic filters.
            $filters = (array)$request->getQuery('filters', array());
            ArrayUtils::filter($filters, 'is_string');
            $route->setParam('filters', $filters);
        }
    }

    public function onRender(MvcEvent $event)
    {
        $app = $event->getApplication();
        $serviceManager = $app->getServiceManager();
        $route = $event->getRouteMatch();
        if (null === $route) {
            return;
        }
        /** @var HelperPluginManager $viewHelperManager */
        $viewHelperManager = $serviceManager->get('viewhelpermanager');

        $viewModel = $event->getViewModel();
        $helpers = array('headTitle', 'pageTitle', 'pageDescription');
        foreach ($helpers as $helperName) {
            $variable = $viewModel->getVariable($helperName, $route->getParam($helperName));
            if ($variable && $viewHelperManager->has($helperName)) {
                $helper = $viewHelperManager->get($helperName);
                if (is_callable($helper)) {
                    $helper($variable, AbstractContainer::SET);
                }
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

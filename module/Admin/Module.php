<?php
namespace Admin;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\ModelInterface;

class Module
{
    /**
     * @param  \Zend\Mvc\MvcEvent $event The MvcEvent instance
     *
     * @return void
     */
    public function onBootstrap($event)
    {
        $app = $event->getApplication();
        $eventManager = $app->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'setLayout'));
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'));
    }

    /**
     * @param \Zend\Mvc\MvcEvent $event The MvcEvent instance
     */
    public function onRoute(MvcEvent $event)
    {
        $app = $event->getApplication();
        $serviceManager = $app->getServiceManager();
        $matches = $event->getRouteMatch();
        if ($this->isAdminRoute($matches->getMatchedRouteName())) {
            /** @var AuthenticationServiceInterface $authenticationService */
            $authenticationService = $serviceManager->get('Zend\Authentication\AuthenticationService');
            if (!$authenticationService->hasIdentity()) {
                $url = $event->getRouter()->assemble(
                    array(),
                    array(
                        'name' => 'admin/login',
                        'query' => array(
                            'destination' => trim($event->getRequest()->getRequestUri(), '/'),
                        ),
                    )
                );
                /** @var ResponseInterface $response */
                $response = $event->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $event->getRequest()->getBaseUrl() . $url);
                $response->setStatusCode(302);
                $response->sendHeaders();
                $event->setError('Login required');

            }
        }
    }

    /**
     * @param  \Zend\Mvc\MvcEvent $event The MvcEvent instance
     */
    public function setLayout($event)
    {
        $viewModel = $event->getViewModel();
        $matches = $event->getRouteMatch();
        $route = $matches->getMatchedRouteName();
        if (!$this->isAdminRoute($route)) {
            if ($route === 'admin/login') {
                $viewModel->setTemplate('layout/admin-login');
                foreach ($viewModel->getChildren() as $childView) {
                    /** @var $childView ModelInterface */
                    if ($childView->captureTo() === 'content') {
                        $childView->setTemplate('admin/login');
                    }
                }

            }
            // not a controller from this module
            return;
        }

        // Set the layout template
        $viewModel->setTemplate('layout/admin');
    }

    protected function isAdminRoute($route)
    {
        return stripos($route, 'admin') === 0 && !in_array($route, array('admin/login'));
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

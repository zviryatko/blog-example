<?php
namespace Admin;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface;

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
        if ($this->isMainLayout($matches->getMatchedRouteName())) {
            /** @var AuthenticationServiceInterface $authenticationService */
            $authenticationService = $serviceManager->get('Zend\Authentication\AuthenticationService');
            if (!$authenticationService->hasIdentity()) {
                $url = $event->getRouter()->assemble(
                    array(),
                    array(
                        'name' => 'admin/login',
                        'query' => array(
                            'destination' => $event->getRequest()->getRequestUri(),
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
        $matches = $event->getRouteMatch();
        if (!$this->isMainLayout($matches->getMatchedRouteName())) {
            // not a controller from this module
            return;
        }

        // Set the layout template
        $viewModel = $event->getViewModel();
        $viewModel->setTemplate('layout/admin');
    }

    protected function isMainLayout($route)
    {
        return stripos($route, 'admin') === 0 && !in_array($route, array('admin/login', 'admin/register'));
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

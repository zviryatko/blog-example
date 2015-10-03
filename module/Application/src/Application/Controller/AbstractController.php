<?php
/**
 * @file
 *
 */

namespace Application\Controller;

use Application\Form\ConfirmForm;
use Zend\Http\Request;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Exception;
use Zend\Mvc\Controller\AbstractController as MvcController;
use Zend\Mvc\MvcEvent;

/**
 * Class AbstractController
 *
 * @method ConfirmForm confirmForm($message, $confirm, $decline)
 * @method Request getRequest()
 *
 * @package Application\Controller
 */
class AbstractController extends MvcController
{
    /**
     * {@inheritDoc}
     */
    protected $eventIdentifier = __CLASS__;

    /**
     * Action called if matched action does not exist
     *
     * @return array
     */
    public function notFoundAction()
    {
        $response = $this->response;
        $event = $this->getEvent();
        $routeMatch = $event->getRouteMatch();
        $routeMatch->setParam('action', 'not-found');

        if ($response instanceof HttpResponse) {
            return $this->createHttpNotFoundModel($response);
        }
        return $this->createConsoleNotFoundModel($response);
    }

    /**
     * @inheritdoc
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            /**
             * @todo Determine requirements for when route match is missing.
             *       Potentially allow pulling directly from request metadata?
             */
            throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $method = static::getMethodFromAction($action);

        if (!method_exists($this, $method)) {
            $method = 'notFoundAction';
        }

        $params = array();
        $entity = $routeMatch->getParam('entity');
        if ($entity) {
            $params[] = $entity;
        }

        $actionResponse = call_user_func_array(array($this, $method), $params);

        $e->setResult($actionResponse);

        return $actionResponse;
    }
}
<?php
/**
 * @file
 *
 */

namespace Application\Controller\Plugin;

use Zend\Http\Request;
use Zend\Mvc\Exception;
use Zend\Mvc\Controller\Plugin\Redirect as BaseRedirect;

class Redirect extends BaseRedirect
{
    protected $response;
    protected $bypassDestination = false;

    /**
     * @inheritdoc
     */
    public function toUrl($url)
    {
        if (!$this->isBypassDestination()) {
            $url = $this->getRequest()->getQuery('destination', $url);
        }

        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode(302);
        return $response;
    }

    /**
     * Get the response
     *
     * @return Request
     * @throws Exception\DomainException if unable to find response
     */
    protected function getRequest()
    {
        if ($this->response) {
            return $this->response;
        }

        $event    = $this->getEvent();
        $request = $event->getRequest();
        if (!$request instanceof Request) {
            throw new Exception\DomainException('Redirect plugin requires event compose a request');
        }
        $this->request = $request;
        return $this->request;
    }

    /**
     * @return boolean
     */
    public function isBypassDestination()
    {
        return $this->bypassDestination;
    }

    /**
     * @param boolean $bypassDestination
     *
     * @return $this
     */
    public function setBypassDestination($bypassDestination)
    {
        $this->bypassDestination = $bypassDestination;

        return $this;
    }
}
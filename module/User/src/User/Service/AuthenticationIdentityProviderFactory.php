<?php
/**
 * @file
 * File short desctiption.
 *
 * Created by PhpStorm.
 * User: Alex Davyskiba
 * Company: HTML&CMS (http://html-and-cms.com)
 * Date: 10/21/15
 */

namespace User\Service;

use User\Provider\Identity\AuthenticationIdentityProvider;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class AuthenticationIdentityProviderFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Authentication\AuthenticationService $authService */
        $authService = $serviceLocator->get('Zend\Authentication\AuthenticationService');
        $simpleIdentityProvider = new AuthenticationIdentityProvider($authService);
        $config = $serviceLocator->get('BjyAuthorize\Config');

        $simpleIdentityProvider->setDefaultRole($config['default_role']);
        $simpleIdentityProvider->setAuthenticatedRole($config['authenticated_role']);

        return $simpleIdentityProvider;
    }
}
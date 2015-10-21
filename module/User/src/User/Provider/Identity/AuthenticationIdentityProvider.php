<?php
/**
 * @file
 */

namespace User\Provider\Identity;

use BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider as BjyAuthorizeAuthenticationIdentityProvider;
use BjyAuthorize\Provider\Role\ProviderInterface as RoleProviderInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

class AuthenticationIdentityProvider extends BjyAuthorizeAuthenticationIdentityProvider
{
    /**
     * {@inheritDoc}
     */
    public function getIdentityRoles()
    {
        if (!$identity = $this->authService->getIdentity()) {
            return array($this->defaultRole);
        }

        if ($identity instanceof RoleInterface) {
            return array($identity);
        }

        if ($identity instanceof RoleProviderInterface) {
            $roles = $identity->getRoles();
            if (!empty($roles)) {
                return $roles;
            }
        }

        return array($this->authenticatedRole);
    }
}
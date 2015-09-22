<?php
/**
 * @file
 *
 */

namespace User\Service;

use User\Entity\User;
use Zend\Crypt\Password\Bcrypt;

/**
 * Class UserService
 *
 * @package User\Service
 */
class UserService
{
    /**
     * Static function for checking hashed password (as required by Doctrine)
     *
     * @param  User   $user     The identity object
     * @param  string $password Password provided by the user, to verify
     *
     * @return boolean If the password was correct or not
     */
    public static function verifyHashedPassword(User $user, $password)
    {
        $bcrypt = new Bcrypt();

        return $bcrypt->verify($password, $user->getPassword());
    }

    /**
     * Hash password.
     *
     * @todo: it must not be here.
     *      Maybe move it to PasswordAwareInterface.
     *      Also need to create bcrypt from factory with local config like salt.
     *
     * @param $password
     *
     * @return string
     */
    public static function hashPassword($password)
    {
        $bcrypt = new Bcrypt();

        return $bcrypt->create($password);
    }
}
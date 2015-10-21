<?php
/**
 * @file
 * User entity.
 */

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use BjyAuthorize\Provider\Role\ProviderInterface as RoleProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Crypt\Password\Bcrypt;

/**
 * @ORM\Entity
 */
class User implements RoleProviderInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $fullName;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $company;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $password;

    /**
     *
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    protected $created;

    /**
     * @var Role[]|\Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="User\Entity\Role", cascade={"persist","remove"})
     * @ORM\JoinTable(name="user_roles")
     */
    protected $roles;

    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->roles = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     *
     * @return User
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @param mixed $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        if (!$this->getId()) {
            $password = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->password = $password;

        return $this;
    }

    /**
     * Check user password.
     *
     * @param User $user
     * @param      $password
     *
     * @return bool
     */
    public static function verifyPassword(User $user, $password)
    {
        return password_verify($password, $user->getPassword());
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     *
     * @return User
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get role.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }


    /**
     * Add role
     *
     * @param Role $role
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;
    }

    /**
     * Remove roles
     *
     * @param Role $roles
     */
    public function removeRole(Role $roles)
    {
        $this->roles->removeElement($roles);
    }
}

<?php
/**
 * @file
 */

namespace User\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use User\Entity\Role;

class LoadUserRoleData extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $userRole = new Role();
        $userRole->setRoleId('user');

        $adminRole = new Role();
        $adminRole->setRoleId('admin');
        $adminRole->setParent($userRole);

        $manager->persist($adminRole);
        $manager->flush();

        $this->addReference('user-role', $userRole);
        $this->addReference('admin-role', $adminRole);
    }
}
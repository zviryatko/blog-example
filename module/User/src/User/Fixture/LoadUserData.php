<?php
/**
 * @file
 */

namespace User\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use User\Entity\Role;
use User\Entity\User;

class LoadUserData extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $demoUser = new User();
        $demoUser->setFullName('Jane Doe');
        $demoUser->setEmail('jane.doe@makeyoulivebetter.org.ua');
        $demoUser->setCompany('MakeYouLiveBetter');
        $demoUser->setPassword('demo');
        /** @var Role $userRole */
        $userRole = $this->getReference('user-role');
        $demoUser->addRole($userRole);

        $manager->persist($demoUser);

        $adminUser = new User();
        $adminUser->setFullName('Alex Davyskiba');
        $adminUser->setEmail('admin@makeyoulivebetter.org.ua');
        $adminUser->setCompany('HTML&CMS');
        $adminUser->setPassword('demo');
        /** @var Role $adminRole */
        $adminRole = $this->getReference('admin-role');
        $adminUser->addRole($adminRole);

        $manager->persist($adminUser);

        $manager->flush();

        $this->addReference('admin-user', $adminUser);
    }

    /**
     * @inheritdoc
     */
    public function getDependencies()
    {
        return ['User\Fixture\LoadUserRoleData'];
    }
}
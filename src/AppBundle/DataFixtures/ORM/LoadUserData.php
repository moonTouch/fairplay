<?php 

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Creates 5 users,
     * named user.$i
     * password user123
     * enabled
     * with ROLE_USER
     * email
     */
    public function load(ObjectManager $manager)
    {
        for ($i= 0; $i <5; $i++) {

            $user = new User();
            $user   
                ->setUsername('user' . $i)
                ->setPlainPassword('user123')
                ->setEnabled(true)
                ->addRole('ROLE_USER')
                ->setEmail('user' . $i . '@gmail.com');

            $manager->persist($user);
            $manager->flush();

            //Creates a reference to use in LoadArticleData
            $this->addReference('user' . $i, $user);
        } 
        
    }

    public function getOrder()
    {
        return 1;
    }
}

<?php

namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;
use AppBundle\Entity\Event;

/**
 * Description of UserTest
 *
 * @author dkociuba
 */
class UserTest extends WebTestCase {

    private $em;

    public function setUp() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        
        // get the Entity Manager
        $this->em = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();
    }
    public function testCreateUser() {
        $user = new User();
        $user->setUsername('joinToEvent');
        $user->setEmail('joinToEvent@example.com');
        $user->setEnabled(true);
        $user->addRole('ROLE_USER');
        $user->setPlainPassword('test');
        $user->setGender(1);
        $user->setBirthDate(new \DateTime('02.01.1999'));

        $this->em->persist($user);
        $this->em->flush();
        
        
        $this->assertNotNull($user->getId());
        
        return $user;
    }

    /**
     * @depends testCreateUser
     * @param User $user
     */
    public function JoinToEvent(User $user) {

        $event = new Event();
        $event->setAddress('Katowice, moniuszki 7');
        $event->setDescription('Some desc.');
        $event->setEndDate(new \DateTime('15.05.2015'));
        $event->setIsPublic(true);
        $event->setEndRegistrationDate(new \DateTime('1.05.2015'));
        $event->setName('Concert test');
        $event->setStartDate(new \DateTime('15.05.2015'));
        $event->setOwner($user);
        $this->em->persist($event);

        $this->em->flush();

        $user->joinToEvent($event);
        
        $this->assertTrue(in_array($event, $user->getJoinedEvents()));
        $this->assertTrue(in_array($user, $event->getJoinedUsers()));
    }

}

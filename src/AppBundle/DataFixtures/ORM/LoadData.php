<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Event;

class LoadData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $eventOwner = $this->loadAdminUser($manager);
        $this->loadEvents($eventOwner, $manager);
    }
    
    private function loadAdminUser(ObjectManager $manager) {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('email@example.com');
        $userAdmin->setEnabled(true);
        $userAdmin->addRole('ROLE_USER');
        $userAdmin->setPlainPassword('test');
        $userAdmin->setGender(1);
        $userAdmin->setBirthDate(new \DateTime('02.01.1999'));

        $manager->persist($userAdmin);
        $manager->flush();
        
        return $userAdmin;
    }
     public function loadEvents(User $eventOwner, ObjectManager $manager)
    {
        $event = new Event();
        $event->setAddress('Katowice, moniuszki 7');
        $event->setDescription('Some desc.');
        $event->setEndDate(new \DateTime('15.05.2015'));
        $event->setIsPublic(true);
        $event->setEndRegistrationDate(new \DateTime('1.05.2015'));
        $event->setName('Concert');
        $event->setStartDate(new \DateTime('15.05.2015'));
        $event->setUser($eventOwner);
        $manager->persist($event);
        
        $event = new Event();
        $event->setAddress('Warszawa, Jana Pawła II 20');
        $event->setDescription('Public event');
        $event->setEndDate(new \DateTime('28.05.2015'));
        $event->setIsPublic(true);
        $event->setEndRegistrationDate(new \DateTime('15.05.2015'));
        $event->setName('Second Concert');
        $event->setStartDate(new \DateTime('27.05.2015'));
        $event->setUser($eventOwner);
        $manager->persist($event);
        
        $event = new Event();
        $event->setAddress('Wrocław, Mickiewicza 50');
        $event->setDescription('Private event');
        $event->setEndDate(new \DateTime('28.05.2015'));
        $event->setIsPublic(false);
        $event->setEndRegistrationDate(new \DateTime('15.05.2015'));
        $event->setName('Private Concert');
        $event->setStartDate(new \DateTime('27.05.2015'));
        $event->setUser($eventOwner);
        $manager->persist($event);
        $manager->flush();
    }
}
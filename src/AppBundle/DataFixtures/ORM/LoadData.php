<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Event;

class LoadData implements FixtureInterface {

    /**
     *
     * @var ObjectManager 
     */
    private $manager;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $this->manager = $manager;
        $eventOwner = $this->loadAdminUser();
        $lastEvent = $this->loadEvents($eventOwner);
        $this->joinUserToEvent($eventOwner, $lastEvent);
    }

    private function loadAdminUser() {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('email@example.com');
        $userAdmin->setEnabled(true);
        $userAdmin->addRole('ROLE_USER');
        $userAdmin->setPlainPassword('test');
        $userAdmin->setGender(1);
        $userAdmin->setBirthDate(new \DateTime('02.01.1999'));

        $this->manager->persist($userAdmin);
        $this->manager->flush();

        return $userAdmin;
    }

    public function loadEvents(User $eventOwner) {
        $event = new Event();
        $event->setAddress('Katowice, moniuszki 7');
        $event->setDescription('Some desc.');
        $event->setEndDate(new \DateTime('15.05.2015'));
        $event->setIsPublic(true);
        $event->setEndRegistrationDate(new \DateTime('1.05.2015'));
        $event->setName('Concert');
        $event->setStartDate(new \DateTime('15.05.2015'));
        $event->setOwner($eventOwner);
        $this->manager->persist($event);

        $event = new Event();
        $event->setAddress('Poznań, Jana Pawła II 20');
        $event->setDescription('Public past event');
        $event->setEndDate(new \DateTime('28.01.2015'));
        $event->setIsPublic(true);
        $event->setEndRegistrationDate(new \DateTime('15.05.2015'));
        $event->setName('Old Concert');
        $event->setStartDate(new \DateTime('27.01.2015'));
        $event->setOwner($eventOwner);
        $event->setMaxMembersNumber(20);
        $this->manager->persist($event);

        $event = new Event();
        $event->setAddress('Wrocław, Mickiewicza 50');
        $event->setDescription('Private event');
        $event->setEndDate(new \DateTime('28.05.2015'));
        $event->setIsPublic(false);
        $event->setEndRegistrationDate(new \DateTime('15.05.2015'));
        $event->setName('Private Concert');
        $event->setStartDate(new \DateTime('27.05.2015'));
        $event->setOwner($eventOwner);
        $this->manager->persist($event);

        $event = new Event();
        $event->setAddress('Warszawa, Jana Pawła II 20');
        $event->setDescription('Public event');
        $event->setEndDate(new \DateTime('28.05.2015'));
        $event->setIsPublic(true);
        $event->setEndRegistrationDate(new \DateTime('15.05.2015'));
        $event->setName('Second Concert');
        $event->setStartDate(new \DateTime('27.05.2015'));
        $event->setOwner($eventOwner);
        $event->setMaxMembersNumber(1);
        $this->manager->persist($event);

        $this->manager->flush();

        return $event;
    }

    public function joinUserToEvent(User $user, Event $event) {
        $user->joinToEvent($event);
        $this->manager->flush();
    }

}

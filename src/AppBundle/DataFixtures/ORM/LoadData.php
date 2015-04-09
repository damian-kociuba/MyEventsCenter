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
        $this->loadCommonUsers();
        $eventOwner = $this->loadAdminUser();
        $lastEvent = $this->loadEvents($eventOwner);
        $this->joinUserToEvent($eventOwner, $lastEvent);
    }

    private function loadCommonUsers() {
        $user = new User();
        $user->setUsername('some user');
        $user->setEmail('some@example.com');
        $user->setEnabled(true);
        $user->addRole('ROLE_USER');
        $user->setPlainPassword('test');
        $user->setGender(1);
        $user->setBirthDate(new \DateTime('02.01.1999'));

        $this->manager->persist($user);
        $this->manager->flush();
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
        $event->setAddress('Olimpijska 11, Katowice');
        $event->setLatitude(50.26763895717659);
        $event->setLongitude(19.027242064476013);
        $event->setDescription('Some desc.');
        $event->setEndDate(new \DateTime('15.05.2015'));
        $event->setIsPublic(true);
        $event->setEndRegistrationDate(new \DateTime('1.05.2015'));
        $event->setName('Concert');
        $event->setStartDate(new \DateTime('15.05.2015'));
        $event->setOwner($eventOwner);
        $this->manager->persist($event);

        $event = new Event();
        $event->setAddress('Ratajczaka 1, Poznań, Polska');
        $event->setLatitude(52.40278543661038);
        $event->setLongitude(16.92344069480896);
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
        $event->setAddress('Łęczyńska 29, Lublin, Polska');
        $event->setLatitude(51.24007674714826);
        $event->setLongitude(22.584822177886963);
        $event->setDescription('Private event');
        $event->setEndDate(new \DateTime('28.05.2015'));
        $event->setIsPublic(false);
        $event->setEndRegistrationDate(new \DateTime('15.05.2015'));
        $event->setName('Private Concert');
        $event->setStartDate(new \DateTime('27.05.2015'));
        $event->setOwner($eventOwner);
        $this->manager->persist($event);

        $event = new Event();
        $event->setAddress('Warszawa, Wawelska 19');
        $event->setLatitude(52.21592940490216);
        $event->setLongitude(20.982331037521362);
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

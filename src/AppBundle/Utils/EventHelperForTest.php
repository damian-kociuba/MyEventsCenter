<?php

namespace AppBundle\Utils;

use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Event;

/**
 * Description of UserHelperForTest
 *
 * @author dkociuba
 */
class EventHelperForTest {

    /**
     * @var ObjectManager 
     */
    private $em;

    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }

    public function createTestEvent(User $eventOwner, $eventName) {
        $event = new Event();
        $event->setAddress('Gdańsk, Spokojna 1');
        $event->setDescription('Public past event');
        $event->setEndDate(new \DateTime('28.10.2015'));
        $event->setIsPublic(true);
        $event->setEndRegistrationDate(new \DateTime('15.05.2015'));
        $event->setName($eventName);
        $event->setStartDate(new \DateTime('27.10.2015'));
        $event->setOwner($eventOwner);
        $event->setMaxMembersNumber(20);
        $this->em->persist($event);
        $this->em->flush();

        return $event;
    }

}

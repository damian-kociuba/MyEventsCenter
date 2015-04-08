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
trait EventHelperForTest {

    /**
     * @var Event
     */
    private static $testEvent;

    public function createTestEvent(ObjectManager $em, User $eventOwner, $eventName) {
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
        $em->persist($event);
        $em->flush();
        
        self::$testEvent = $event;
        return $event;
    }

}

<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\EventRepository;
use AppBundle\Entity\Event;

class ShowEventController extends Controller
{
    public function indexAction($eventId)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($eventId);
        
        $eventManager = $this->get('app.event_manager');
        return $this->render('event/show.html.twig', array(
            'event' => $event,
            'isCurrentUserMember' => $eventManager->isUserMemberOfEvent($this->getUser(), $event),
            'isCurrentUserOwner' => $eventManager->isUserOwnerOfEvent($this->getUser(), $event),
            'isEventPublic' => $event->getIsPublic(),
            'allowedToJoin' => $eventManager->isUserAllowedToJoinEvent($this->getUser(), $event)
        ));
    }
}
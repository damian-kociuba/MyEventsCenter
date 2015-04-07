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
        $eventRepo = $em->getRepository('AppBundle:Event');
        $event = $eventRepo->find($eventId);
        
        return $this->render('event/show.html.twig', array(
            'event' => $event,
            'isCurrentUserMember' => $this->isCurrentUserMemberOfEvent($event, $eventRepo)
        ));
    }
    
    private function isCurrentUserMemberOfEvent(Event $event, EventRepository $eventRepo) {
        if($this->getUser() === null) {
            return null;
        }
        return $eventRepo->isUserJoinedToEvent($this->getUser(), $event);
    }
    
}
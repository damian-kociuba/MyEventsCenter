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
            'isCurrentUserMember' => $this->isCurrentUserMemberOfEvent($event, $eventRepo),
            'isCurrentUserOwner' => $this->isCurrentUserOwnerOfEvent($event, $eventRepo),
            'isEventPublic' => $event->getIsPublic(),
            'allowedToJoin' => $event->getIsPublic() || (!$event->getIsPublic() && $this->isUserInvitedToEvent($event))
        ));
    }
    
    private function isCurrentUserMemberOfEvent(Event $event, EventRepository $eventRepo) {
        if($this->getUser() === null) {
            return null;
        }
        return $eventRepo->isUserJoinedToEvent($this->getUser(), $event);
    }
    
    private function isCurrentUserOwnerOfEvent(Event $event) {
        if($this->getUser() === null) {
            return null;
        }
        return $this->getUser()->getId() === $event->getOwner()->getId();
    }
    
    private function isUserInvitedToEvent(Event $event) {
        $invitation = $event->getInvitation();
        $user = $this->getUser();
        if($invitation === null || $user === null) {
            return false;
        }
        
        foreach($invitation->getReceivers() as $receiver) {
            if($receiver->getId() === $user->getId()) 
            {
                return true;
            }
        }
        
        return false;
    }
    
}
<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\EventRepository;
use AppBundle\Entity\Event;
use AppBundle\Entity\Invitation;

class SendInvitationController extends Controller {

    public function indexAction(Request $request, $eventId) {
        $em = $this->getDoctrine()->getManager();
        $usersRepo = $em->getRepository('AppBundle:User');
        $eventRepo = $em->getRepository('AppBundle:Event');
        $event = $eventRepo->find($eventId);
        if ($event->getOwner()->getId() !== $this->getUser()->getId()) {
            throw new \Symfony\Component\Security\Acl\Exception\Exception('Not permitted');
        }
        $uninvitedUsers = $usersRepo->findAll();
        $invitedUsers = $this->getInvitedUsers($event);
        return $this->render('invitation/send.html.twig', array(
                    'uninvitedUsers' => array_diff($uninvitedUsers,$invitedUsers->toArray()),
                    'invitedUsers' => $invitedUsers,
                    'description' => $this->getDescriptionOfInvitation($event)
        ));
    }

    private function getInvitedUsers(Event $event) {
        $invitation = $event->getInvitation();
        if($invitation === null) {
            return array();
        }
        
        return $event->getInvitation()->getReceivers();
    }
    private function getDescriptionOfInvitation(Event $event) {
        $invitation = $event->getInvitation();
        if($invitation === null) {
            return array();
        }
        
        return $event->getInvitation()->getDescription();
    }
    public function handleFormAction(Request $request, $eventId) {
        $em = $this->getDoctrine()->getManager();
        $selectedUsers = $request->request->get('selectedUsers');
        if(empty($selectedUsers)) {
            $selectedUsers = array();
        }
        $usersRepo = $em->getRepository('AppBundle:User');
        $eventRepo = $em->getRepository('AppBundle:Event');
        $event = $eventRepo->find($eventId);
        $invitation = $this->getOrCreateEventInvitation($event, $em);
        $invitation->setSender($this->getUser());
        $invitation->setEvent($event);
        $invitation->setDescription($request->request->get('description'));
        
        $invitation->removeAllReceiver();
        foreach ($selectedUsers as $userIdToInvite) {
            $userToInvite = $usersRepo->find($userIdToInvite);
            $invitation->addReceiver($userToInvite);
        }
        $em->flush();
        return $this->redirectToRoute('show_event', array('eventId' => $eventId));
    }
    
    private function getOrCreateEventInvitation(Event $event, $em) {
        $invitation = $event->getInvitation();
        if($invitation === null) {
            $invitation = new Invitation();
            $event->setInvitation($invitation);
            $em->persist($invitation);
        } 
        return $invitation;
    }

}

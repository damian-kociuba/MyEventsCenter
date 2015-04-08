<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\EventRepository;
use AppBundle\Entity\Event;

class DeleteUsersFromEventController extends Controller
{
    public function indexAction($eventId)
    {
        $em = $this->getDoctrine()->getManager();
        $eventRepo = $em->getRepository('AppBundle:Event');
        $event = $eventRepo->find($eventId);
        
        return $this->render('event/delete_users.html.twig', array(
            'event' => $event,
        ));
    }
    
    public function handleFormAction(Request $request, $eventId) {
        $em = $this->getDoctrine()->getManager();
        $selectedUsers = $request->request->get('userIdToDelete');
        if(empty($selectedUsers)) {
            $selectedUsers = array();
        }
        $usersRepo = $em->getRepository('AppBundle:User');
        $eventRepo = $em->getRepository('AppBundle:Event');
        $event = $eventRepo->find($eventId);
        foreach ($selectedUsers as $userIdToRemove) {
            $userToRemove = $usersRepo->find($userIdToRemove);
            $event->removeJoinedUser($userToRemove);
        }
        $em->flush();
        return $this->redirectToRoute('show_event', array('eventId' => $eventId));
    }
    
}
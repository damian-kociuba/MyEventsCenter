<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JoinToEventController extends Controller {

    public function indexAction($eventId) {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Event')->find($eventId);

        $user = $this->getUser();
        $user->joinToEvent($event);
        $em->flush();

        return $this->redirectToRoute('show_event', array('eventId' => $event->getId()));
    }

}

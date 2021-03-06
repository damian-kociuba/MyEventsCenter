<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ResignEventController extends Controller {

    public function indexAction($eventId) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT e FROM AppBundle:Event e WHERE e.id=:eventId')
                ->setParameter('eventId', $eventId);
        $event = $query->getSingleResult();

        $user = $this->getUser();
        $user->resignFromEvent($event);
        $em->flush();

        return $this->redirectToRoute('show_event', array('eventId' => $event->getId()));
    }

}

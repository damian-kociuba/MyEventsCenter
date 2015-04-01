<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShowEventController extends Controller
{
    public function indexAction($eventId)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT e FROM AppBundle:Event e WHERE e.id=:eventId')
                ->setParameter('eventId', $eventId);
        $event = $query->getSingleResult();
        
        return $this->render('event/show.html.twig', array(
            'event' => $event,
        ));
    }
}
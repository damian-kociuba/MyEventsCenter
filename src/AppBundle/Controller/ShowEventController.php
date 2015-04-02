<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\EventRepository;

class ShowEventController extends Controller
{
    public function indexAction($eventId)
    {
        $em = $this->getDoctrine()->getManager();
        $eventRepo = $em->getRepository('AppBundle:Event');
        $event = $eventRepo->find($eventId);
        
        return $this->render('event/show.html.twig', array(
            'event' => $event,
        ));
    }
}
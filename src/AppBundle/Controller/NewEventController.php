<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\EventRepository;
use AppBundle\Entity\Event;
use AppBundle\Form\Type\EventType;

class NewEventController extends Controller {

    public function indexAction(Request $request) {
        $event = new Event();
        $form = $this->createForm(new EventType(), $event);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event->setOwner($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            // perform some action, such as saving the task to the database

            return $this->redirectToRoute('show_event', array('eventId'=>$event->getId()));
        }
        return $this->render('event/new.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

}

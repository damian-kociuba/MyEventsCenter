<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\EventRepository;
use AppBundle\Entity\Event;

class NewEventController extends Controller {

    public function indexAction(Request $request) {
        $event = new Event();
        $form = $this->createFormBuilder($event)
                ->add('name', 'text')
                ->add('address', 'text')
                ->add('description', 'text')
                ->add('maxMembersNumber', 'number')
                ->add('isPublic', 'choice', array(
                    'choices' => array('1' => 'Yes', '0' => 'No'),
                ))
                ->add('startDate', 'date')
                ->add('endDate', 'date')
                ->add('endRegistrationDate', 'date')
                ->add('save', 'submit')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            var_dump($event);
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

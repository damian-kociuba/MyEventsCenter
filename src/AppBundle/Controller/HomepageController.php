<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT e FROM AppBundle:Event e WHERE e.isPublic=true AND e.endDate>=:now')
                ->setParameter('now', new \DateTime);
        
        return $this->render('index.html.twig', array(
            'nearestEvents' => $query->getResult(),
        ));
    }
}

<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT e FROM AppBundle:Event e WHERE e.isPublic=true');
        
        return $this->render('index.html.twig', array(
            'nearestEvents' => $query->getResult(),
        ));
    }
}

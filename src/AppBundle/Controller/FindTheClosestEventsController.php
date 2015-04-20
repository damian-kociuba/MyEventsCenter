<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FindTheClosestEventsController extends Controller {

    public function indexAction(\Symfony\Component\HttpFoundation\Request $request) {
        $post = json_decode($request->getContent(), true);

        $latitude = filter_var($post['latitude'], FILTER_VALIDATE_FLOAT);
        $longitude = filter_var($post['longitude'], FILTER_VALIDATE_FLOAT);
        $em = $this->getDoctrine()->getManager();

        $events =  $em->getRepository('AppBundle:Event')->findTheClosest($latitude, $longitude, 5);
       
        foreach($events as &$event) {
            $event['path'] = $this->generateUrl('show_event', array('eventId'=>$event['id']));
        }
        $response = new \Symfony\Component\HttpFoundation\JsonResponse();
        $response->setData(array(
            'events' => $events
        ));

        return $response;
    }

}

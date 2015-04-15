<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FindTheClosestEventsController extends Controller {

    public function indexAction(\Symfony\Component\HttpFoundation\Request $request) {
        $post = json_decode($request->getContent(), true);

        $latitude = filter_var($post['latitude'], FILTER_VALIDATE_FLOAT);
        $longitude = filter_var($post['longitude'], FILTER_VALIDATE_FLOAT);
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getConnection()->createQueryBuilder();
        $queryBuilder
                ->select('id', 'isPublic', 'name', 'description', 'startDate', 'endDate', 'maxMembersNumber', 'endRegistrationDate', 'address', '(6371 * acos( cos( radians( '.$latitude.' ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( '.$longitude.' ) ) + sin( radians( '.$latitude.' ) ) * sin( radians( latitude ) ) )) AS distance')
                ->from('Event', 'e')
                ->having('distance<5');
                
        //$query = $queryBuilder->getQuery();
        $stmt = $em->getConnection()->executeQuery($queryBuilder->getSql());

        $events = $stmt->fetchAll();
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

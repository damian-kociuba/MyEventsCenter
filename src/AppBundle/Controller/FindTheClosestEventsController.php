<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FindTheClosestEventsController extends Controller {

    public function indexAction(\Symfony\Component\HttpFoundation\Request $request) {
        $post = json_decode($request->getContent(), true);

        $latitude = $post['latitude'];
        $longitude = $post['longitude'];
        $em = $this->getDoctrine()->getManager();

        $sql = 'SELECT id, isPublic, name, description, startDate,endDate,maxMembersNumber, endRegistrationDate, address, (
6371 * acos( cos( radians( 52 ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( 20 ) ) + sin( radians( 52 ) ) * sin( radians( latitude ) ) )
) AS distance
FROM Event
HAVING distance <100
ORDER BY `Event`.`id` ASC
LIMIT 0 , 20';
//        $stmt = $em->getConnection()->prepare($sql);
//        
//        $stmt->execute();
        $queryBuilder = $em->getConnection()->createQueryBuilder();
        $queryBuilder
                ->select('id', 'isPublic', 'name', 'description', 'startDate', 'endDate', 'maxMembersNumber', 'endRegistrationDate', 'address', '(6371 * acos( cos( radians( 52 ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( 20 ) ) + sin( radians( 52 ) ) * sin( radians( latitude ) ) )) AS distance')
                ->from('Event')
                ->having('distance<100');
        $query = $queryBuilder->getQuery();
        $stmt = $query->getResult();        
        $response = new \Symfony\Component\HttpFoundation\JsonResponse();
        $response->setData(array(
            'events' => $stmt->fetchAll()
        ));

        return $response;
    }

}

<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\Client;
use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use AppBundle\Utils\UserHelperForTest;
use AppBundle\Utils\EventHelperForTest;

class FindTheClosestEventsControllerTest extends WebTestCase {

    const TEST_USER_PASSWORD = 'test';

    use \AppBundle\Utils\Test\UserHelper;

    use \AppBundle\Utils\Test\EventHelper;

    /**
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private static $em;

    public static function setUpBeforeClass() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        // get the Entity Manager
        self::$em = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();

        self::createTestUser(self::$em, 'ClosestEventsUser', self::TEST_USER_PASSWORD);

        //create event with position which corresponds to address 'Krakowska 32, Częstochowa, Polska'
        self::createTestEvent(self::$em, self::$testUser, 'Close test Event', array('latitude' => 50.80593472676908, 'longitude' => 19.127197265625));
    }

    /**
     * @var Client
     */
    private $client;

    public function testIsResponseCorrectJSON() {
        $this->client = static::createClient();
        //search by coordinate in the same city
        $requestContent = json_encode(array('latitude' => 50.809789, 'longitude' => 19.127433));
        $this->client->request('POST', '/event/find_the_closest', array(), array(), array(), $requestContent);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode()); 
        $this->assertEquals('application/json', $this->client->getResponse()->headers->get('Content-Type'));

        return $this->client;
    }

    /**
     * @depends testIsResponseCorrectJSON
     * @param Client $client
     */
    public function testDoesResponseConsistOneEvent(Client $client) {
        #$query = self::$em->createQuery('SELECT e FROM AppBundle:Event e WHERE e.name = \'Close test Event\'');
        #$event= $query->getResult();
        #var_dump($event);
        $content = json_decode($client->getResponse()->getContent(), true); 
        #var_dump($content);
        $this->assertEquals(1, count($content['events']));
    }

}

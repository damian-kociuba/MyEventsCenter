<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class NewEventControllerTest extends WebTestCase {

    use \AppBundle\Utils\Test\UserHelper;

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

        self::createTestUser(self::$em, 'NewEventTest', 'pass');

        self::$em->flush();
    }

    /**
     * @var Client
     */
    private static $client;
    private $testEventProperties;

    public function __construct() {
        parent::__construct();
        $this->testEventProperties = array(
            'name' => 'Test Event śłó !!//^&',
            'address' => 'Fryderyka Chopina 1, Katowice, Polska',
            'latitude' => 50.2612538275847,
            'longitude' => 19.017333984375,
            'description' => 'Some description ' . str_repeat('a', 237), // 255-chars-length string
            'maxMembersNumber' => 10,
            'isPublic' => 1,
            'startDate' => array('year' => 2015, 'month' => 5, 'day' => 6),
            'endDate' => array('year' => 2015, 'month' => 5, 'day' => 8),
            'endRegistrationDate' => array('year' => 2015, 'month' => 4, 'day' => 6),
        );
    }

    public function testLogin() {
        self::$client = static::createClient();

        self::loginAsTestUser(self::$client);
        $this->assertEquals('AppBundle\Controller\HomepageController::indexAction', self::$client->getRequest()->attributes->get('_controller'));

        return self::$client;
    }

    /**
     * @depends testLogin
     */
    public function testNewEventHttpCode200() {
        $crawler = self::$client->request('GET', '/event/new');
        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());

        return $crawler;
    }

    /**
     * @depends testNewEventHttpCode200
     * @param Crawler $crawler
     */
    public function testAddNewEventRedirectToEventPreview(Crawler $crawler) {

        $form = $crawler->selectButton('Save')->form();

        $form->setValues(array(
            'eventForm[name]' => $this->testEventProperties['name'],
            'eventForm[address]' => $this->testEventProperties['address'],
            'eventForm[longitude]' => $this->testEventProperties['longitude'],
            'eventForm[latitude]' => $this->testEventProperties['latitude'],
            'eventForm[description]' => $this->testEventProperties['description'],
            'eventForm[maxMembersNumber]' => $this->testEventProperties['maxMembersNumber'],
            'eventForm[isPublic]' => $this->testEventProperties['isPublic'],
            'eventForm[startDate][year]' => $this->testEventProperties['startDate']['year'],
            'eventForm[startDate][month]' => $this->testEventProperties['startDate']['month'],
            'eventForm[startDate][day]' => $this->testEventProperties['startDate']['day'],
            'eventForm[endDate][year]' => $this->testEventProperties['endDate']['year'],
            'eventForm[endDate][month]' => $this->testEventProperties['endDate']['month'],
            'eventForm[endDate][day]' => $this->testEventProperties['endDate']['day'],
            'eventForm[endRegistrationDate][year]' => $this->testEventProperties['endRegistrationDate']['year'],
            'eventForm[endRegistrationDate][month]' => $this->testEventProperties['endRegistrationDate']['month'],
            'eventForm[endRegistrationDate][day]' => $this->testEventProperties['endRegistrationDate']['day'],
        ));

        self::$client->submit($form);
        self::$client->followRedirect();

        $this->assertEquals('AppBundle\Controller\ShowEventController::indexAction', self::$client->getRequest()->attributes->get('_controller'));
    }

    /**
     * @depends testAddNewEventRedirectToEventPreview
     */
    public function testIsNewEventInDatabase() {

        $query = self::$em->createQuery('SELECT e FROM AppBundle:Event e WHERE e.name = :name');
        $query->setParameters(array(
            'name' => $this->testEventProperties['name'],
        ));
        $events = $query->getResult();
        $this->assertEquals(1, count($events), 'Number of saved events should be 1');

        $event = $events[0];
        $this->assertEquals($event->getName(), $this->testEventProperties['name']);
    }

}

<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\Client;
use AppBundle\Entity\Event;
use AppBundle\Entity\User;

class JoinToEventEventControllerTest extends WebTestCase {

    const TEST_USER_PASSWORD = 'test';

    private static $em;
    private static $testUser;
    private static $testEvent;

    public static function setUpBeforeClass() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        // get the Entity Manager
        self::$em = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();

        self::$testUser = self::createTestUser();
        self::$testEvent = self::createTestEvent(self::$testUser);
    }

    private static function createTestUser() {
        $testUser = new User();
        $testUser->setUsername('joinUser');
        $testUser->setEmail('joinUser@example.com');
        $testUser->setEnabled(true);
        $testUser->addRole('ROLE_USER');
        $testUser->setPlainPassword(self::TEST_USER_PASSWORD);
        $testUser->setGender(1);
        $testUser->setBirthDate(new \DateTime('02.01.1999'));


        self::$em->persist($testUser);
        self::$em->flush();
        return $testUser;
    }

    private static function createTestEvent($eventOwner) {
        $event = new Event();
        $event->setAddress('Gdańsk, Spokojna 1');
        $event->setDescription('Public past event');
        $event->setEndDate(new \DateTime('28.10.2015'));
        $event->setIsPublic(true);
        $event->setEndRegistrationDate(new \DateTime('15.05.2015'));
        $event->setName('JoinToEventTest Name');
        $event->setStartDate(new \DateTime('27.10.2015'));
        $event->setOwner($eventOwner);
        $event->setMaxMembersNumber(20);
        self::$em->persist($event);
        self::$em->flush();

        return $event;
    }

    /**
     * @var Client
     */
    private $client;

    /**
     * Step 1
     */
    public function testLogin() {
        $this->client = static::createClient();

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('_submit')->form(array(
            '_username' => self::$testUser->getUsername(),
            '_password' => self::TEST_USER_PASSWORD,
        ));
        $this->client->submit($form);
        $this->client->followRedirect(); // "/" page
        $this->assertEquals('AppBundle\Controller\HomepageController::indexAction', $this->client->getRequest()->attributes->get('_controller'));

        return $this->client;
    }

    /**
     * Step 2
     * @depends testLogin
     * @param Client client
     */
    public function testJoinButtonBeforeJoinExists(Client $client) {
        $crawler = $client->request('GET', '/event/' . self::$testEvent->getId());
        $this->assertEquals(1, $crawler->filter("#joinButton")->count());
    }

    /**
     * Step 2
     * @depends testLogin
     * @param Client client
     */
    public function testResignButtonBeforeJoinNoExists(Client $client) {
        $crawler = $client->request('GET', '/event/' . self::$testEvent->getId());
        $this->assertEquals(0, $crawler->filter("#resignButton")->count());
    }

    /**
     * Step 2
     * @depends testLogin
     * @param Client client
     */
    public function testJoinToEventRedirectToEventPreview(Client $client) {

        $client->request('GET', '/event/join/' . self::$testEvent->getId());
        $client->followRedirect();

        $this->assertEquals('AppBundle\Controller\ShowEventController::indexAction', $client->getRequest()->attributes->get('_controller'));

        return $client;
    }

    /**
     * Step 3
     * @depends testJoinToEventRedirectToEventPreview
     */
    public function testIsConnectionBetweenCurrentUserAndEvent() {
        //getting the same event from database
        $query = self::$em->createQuery("SELECT e FROM AppBundle:Event e  WHERE e.id=:eventId")
                ->setParameter('eventId', self::$testEvent->getId());

        $event = $query->getSingleResult();
        $foundCurrentUser = false;
        foreach ($event->getJoinedUsers() as $user) {
            if ($user->getId() === self::$testUser->getId()) {
                $foundCurrentUser = true;
            }
        }
        $this->assertTrue($foundCurrentUser);
    }

    /**
     * Step 4
     * @depends testJoinToEventRedirectToEventPreview
     * @param Client client
     */
    public function testJoinButtonAfterJoinNoExists(Client $client) {
        $crawler = $client->request('GET', '/event/' . self::$testEvent->getId());
        $this->assertEquals(0, $crawler->filter("#joinButton")->count());
    }

    /**
     * Step 4
     * @depends testJoinToEventRedirectToEventPreview
     * @param Client client
     */
    public function testResignButtonAfterJoinExists(Client $client) {
        $crawler = $client->request('GET', '/event/' . self::$testEvent->getId());
        $this->assertEquals(1, $crawler->filter("#resignButton")->count());
    }

}

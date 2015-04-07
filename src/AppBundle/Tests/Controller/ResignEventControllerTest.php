<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\Client;
use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use AppBundle\Utils\UserHelperForTest;
use AppBundle\Utils\EventHelperForTest;

class ResignEventEventControllerTest extends WebTestCase {

    const TEST_USER_PASSWORD = 'test';

    /**
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private static $em;

    /**
     *
     * @var User
     */
    private static $testUser;

    /**
     *
     * @var Event
     */
    private static $testEvent;

    /**
     * @var UserHelperForTest
     */
    private static $userHelperForTest;

    /**
     * @var EventHelperForTest
     */
    private static $eventHelperForTest;

    public static function setUpBeforeClass() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();
        // get the Entity Manager
        self::$em = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();

        self::$userHelperForTest = new UserHelperForTest(self::$em);
        self::$eventHelperForTest = new EventHelperForTest(self::$em);
        self::$testUser = self::$userHelperForTest->createTestUser('ResignUser', self::TEST_USER_PASSWORD);
        self::$testEvent = self::$eventHelperForTest->createTestEvent(self::$testUser, 'Join test Event');
        
        self::$testUser->joinToEvent(self::$testEvent);
        self::$em->flush();
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

        self::$userHelperForTest->loginAsTestUser($this->client);
        
        $this->assertEquals('AppBundle\Controller\HomepageController::indexAction', $this->client->getRequest()->attributes->get('_controller'));

        return $this->client;
    }

    /**
     * Step 2
     * @depends testLogin
     * @param Client client
     */
    public function testResignButtonExists(Client $client) {
        $crawler = $client->request('GET', '/event/' . self::$testEvent->getId());
        $this->assertEquals(1, $crawler->filter("#resignButton")->count());
    }

    /**
     * Step 2
     * @depends testLogin
     * @param Client client
     */
    public function testResignEventRedirectToEventPreview(Client $client) {

        $client->request('GET', '/event/resign/' . self::$testEvent->getId());
        $client->followRedirect();

        $this->assertEquals('AppBundle\Controller\ShowEventController::indexAction', $client->getRequest()->attributes->get('_controller'));

        return $client;
    }

    /**
     * Step 3
     * @depends testResignEventRedirectToEventPreview
     */
    public function testIsNotUserMemberOfEventAfterResign() {
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
        $this->assertFalse($foundCurrentUser);
    }

}

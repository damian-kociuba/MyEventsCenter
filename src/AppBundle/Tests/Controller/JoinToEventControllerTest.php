<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\Client;
use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use AppBundle\Utils\UserHelperForTest;
use AppBundle\Utils\EventHelperForTest;

class JoinToEventEventControllerTest extends WebTestCase {

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

        self::createTestUser(self::$em, 'JoinUser', self::TEST_USER_PASSWORD);
        self::createTestEvent(self::$em, self::$testUser, 'Join test Event');
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

        self::loginAsTestUser($this->client);

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

<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Bundle\FrameworkBundle\Client;

class DeleteUsersFromEventControllerTest extends WebTestCase {

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

        self::createTestUser(self::$em, 'DeleteUserFromEventTest', 'pass');
        self::createTestEvent(self::$em, self::$testUser, 'DeleteUserFromEventTest Event');

        self::$testUser->joinToEvent(self::$testEvent);
        self::$em->flush();
    }

    /**
     * @var Client
     */
    private static $client;

    public function testLogin() {
        self::$client = static::createClient();

        self::loginAsTestUser(self::$client);
        $this->assertEquals('AppBundle\Controller\HomepageController::indexAction', self::$client->getRequest()->attributes->get('_controller'));

        return self::$client;
    }

    /**
     * @depends testLogin
     */
    public function testShowSelectUsersToRemoveHttpCode200() {
        $crawler = self::$client->request('GET', '/event/select_user_to_remove/' . self::$testEvent->getId());
        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());

        return $crawler;
    }

    /**
     * @depends testShowSelectUsersToRemoveHttpCode200
     * @param Crawler $crawler
     */
    public function testAreUsersOnTheList(Crawler $crawler) {
        $this->assertEquals(1, $crawler->filter("input[name='userIdToDelete[]']")->count());
    }

    /**
     * @depends testShowSelectUsersToRemoveHttpCode200
     * @param Crawler $crawler
     */
    public function testRedirectToEventPreviewAfterRemove(Crawler $crawler) {
        $form = $crawler->selectButton('Remove selected users from event')->form();
        $form['userIdToDelete'][0]->tick();

        self::$client->submit($form);
        self::$client->followRedirect();
        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());
        $this->assertEquals('AppBundle\Controller\ShowEventController::indexAction', self::$client->getRequest()->attributes->get('_controller'));
    }
    
    /**
     * @depends testRedirectToEventPreviewAfterRemove
     */
    public function testIsUserRemoveFromEvent() {
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

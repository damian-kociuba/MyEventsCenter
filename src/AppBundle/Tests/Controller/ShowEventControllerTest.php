<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ShowEventControllerTest extends WebTestCase {

    private $em;

    protected function setUp() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();

        // get the Entity Manager
        $this->em = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();
    }

    public function testShowEventHttpCode200() {
        $client = static::createClient();

        $query = $this->em->createQuery("SELECT e FROM AppBundle:Event e");
        $exampleEvent = $query->setMaxResults(1)->getSingleResult();

        $crawler = $client->request('GET', '/event/' . $exampleEvent->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        return $crawler;
    }

    /**
     * @depends testShowEventHttpCode200
     * @param Crawler $crawler
     */
    public function testIsHiddenJoinButtonForUnauthorizedUsers(Crawler $crawler) {
        $this->assertEquals(0, $crawler->filter("#joinButton")->count());
    }

    public function testIsVisibleJoinButtonForUsers() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('_submit')->form(array(
            '_username' => 'admin',
            '_password' => 'test',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect(); // "/" page

        $query = $this->em->createQuery("SELECT e FROM AppBundle:Event e");
        $exampleEvent = $query->setMaxResults(1)->getSingleResult();

        $crawler = $client->request('GET', '/event/' . $exampleEvent->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter("#joinButton")->count());
    }
    

}

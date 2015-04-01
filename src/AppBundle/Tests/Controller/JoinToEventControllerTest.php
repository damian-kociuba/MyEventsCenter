<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\Client;

class JoinToEventEventControllerTest extends WebTestCase {

    private $em;

    protected function setUp() {

        static::$kernel = static::createKernel();
        static::$kernel->boot();

        // get the Entity Manager
        $this->em = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();
    }
    //TODO
    public function testJoinToEventRedirect() {
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

        $client->request('GET', '/event/join/' . $exampleEvent->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        return $client;
    }

    #/**
    # * @depends testShowEventHttpCode200
    # * @param Client $client
    # */
    #public function testIsRedirect(Client $client) {
    #    $client->
    #}
}

<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase {

    use \AppBundle\Utils\DatabaseHelperForTests;

    protected function setUp() {
        $this->dropDatabase();
        $this->createDatabase();
        $this->createSchema();
        $this->loadFixtures();
    }

    public function testIndex() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        echo $client->getResponse()->getStatusCode();
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        $events = $crawler->filter('.event');
        $this->assertEquals(2, $events->count());
        
    }

}

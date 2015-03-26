<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase {

    protected function setUp() {
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
        self::runCommand('doctrine:fixtures:load --purge-with-truncate');
    }

    public function testIndex() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        echo $client->getResponse()->getStatusCode();
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
    }

}

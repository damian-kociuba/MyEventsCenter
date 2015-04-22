<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class HomepageControllerTest extends WebTestCase {

    use \AppBundle\Utils\Test\DatabaseHelper;

    
    private $em;
    
    protected function setUp() {
        $this->dropDatabase();
        $this->createDatabase();
        $this->createSchema();
        $this->loadFixtures();
        
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        
        // get the Entity Manager
        $this->em = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();
    }

    public function testHomepageHttpCode200() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        return $crawler;
    }
    /**
     * @depends testHomepageHttpCode200
     * @param Crawler $crawler
     */
    public function testOnlyNewestPublicEventsVisible(Crawler $crawler) {
        $query = $this->em->createQuery("SELECT count(e.id) FROM AppBundle:Event e WHERE e.isPublic=true AND e.endDate>=:now")
                ->setParameter('now', new \DateTime);
        $numberOfEventsToShow = $query->getSingleScalarResult();
        
        $events = $crawler->filter('div#comming .event');
        $this->assertEquals($events->count(), $numberOfEventsToShow);
        
    }

}

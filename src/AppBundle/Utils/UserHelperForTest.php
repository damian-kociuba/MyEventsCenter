<?php

namespace AppBundle\Utils;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Client;
use AppBundle\Entity\User;

/**
 * Description of UserHelperForTest
 *
 * @author dkociuba
 */
class UserHelperForTest {

    /**
     * @var ObjectManager 
     */
    private $em;
    private $userName;
    private $password;

    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }

    public function createTestUser($userName, $password) {
        $this->userName = $userName;
        $this->password = $password;
        $testUser = new User();
        $testUser->setUsername($this->userName);
        $testUser->setEmail($this->userName . '@example.com');
        $testUser->setEnabled(true);
        $testUser->addRole('ROLE_USER');
        $testUser->setPlainPassword($this->password);
        $testUser->setGender(1);
        $testUser->setBirthDate(new \DateTime('02.01.1999'));


        $this->em->persist($testUser);
        $this->em->flush();
        return $testUser;
    }

    public function loginAsTestUser(Client $client) {
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('_submit')->form(array(
            '_username' => $this->userName,
            '_password' => $this->password,
        ));
        $client->submit($form);
        $client->followRedirect(); // "/" page

        return $client;
    }

}

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
trait UserHelperForTest {

    private static $userHelperUsername;
    private static $userHelperPassword;

    /**
     * @var User
     */
    private static $testUser;

    public static function createTestUser(ObjectManager $em, $userName, $password) {
        self::$userHelperUsername = $userName;
        self::$userHelperPassword = $password;
        $testUser = new User();
        $testUser->setUsername(self::$userHelperUsername);
        $testUser->setEmail(self::$userHelperUsername . '@example.com');
        $testUser->setEnabled(true);
        $testUser->addRole('ROLE_USER');
        $testUser->setPlainPassword(self::$userHelperPassword);
        $testUser->setGender(1);
        $testUser->setBirthDate(new \DateTime('02.01.1999'));
 

        $em->persist($testUser);
        $em->flush();
        self::$testUser = $testUser;
        return $testUser;
    }

    public static function loginAsTestUser(Client $client) {
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('_submit')->form(array(
            '_username' => self::$userHelperUsername,
            '_password' => self::$userHelperPassword,
        ));
        $client->submit($form);
        $client->followRedirect(); // "/" page

        return $client;
    }

}

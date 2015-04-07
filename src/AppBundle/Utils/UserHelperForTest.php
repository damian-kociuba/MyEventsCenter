<?php

namespace AppBundle\Utils;

use Doctrine\Common\Persistence\ObjectManager;
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

    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }

    public function createTestUser($userName, $password) {
        $testUser = new User();
        $testUser->setUsername($userName);
        $testUser->setEmail($userName . '@example.com');
        $testUser->setEnabled(true);
        $testUser->addRole('ROLE_USER');
        $testUser->setPlainPassword($password);
        $testUser->setGender(1);
        $testUser->setBirthDate(new \DateTime('02.01.1999'));


        $this->em->persist($testUser);
        $this->em->flush();
        return $testUser;
    }


}

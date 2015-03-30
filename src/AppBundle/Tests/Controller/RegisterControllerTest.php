<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class RegisterControllerTest extends WebTestCase {

    use \AppBundle\Utils\DatabaseHelperForTests;
    
    protected function setUp() {
        $this->dropDatabase();
        $this->createDatabase();
        $this->createSchema();
        $this->loadFixtures();
    }

    public function testRegister() {

       /* $kernel = static::createKernel();
        $kernel->boot();
        $conn = $kernel->getContainer()->get('database_connection');
        var_dump($conn);*/

        $client = static::createClient();
        $crawler = $client->request('GET', '/register/');

        $this->assertEquals('FOS\UserBundle\Controller\RegistrationController::registerAction', $client->getRequest()->attributes->get('_controller'));
        
        $selectButton = $crawler->selectButton('Register');
        $this->assertEquals(1, $selectButton->count()); //is there the button?
        
        $form = $selectButton->form(array(
            'fos_user_registration_form[username]' => 'login_test',
            'fos_user_registration_form[email]' => 'email_test@test.com',
            'fos_user_registration_form[plainPassword][first]' => 'password_test',
            'fos_user_registration_form[plainPassword][second]' => 'password_test',
            'fos_user_registration_form[gender]' => '1',
            'fos_user_registration_form[birthDate][year]' => '1999',
            'fos_user_registration_form[birthDate][month]' => '11',
            'fos_user_registration_form[birthDate][day]' => '10',
        ));

        $client->submit($form);

        $kernel = static::createKernel();
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $query = $em->createQuery("SELECT u FROM AppBundle:User u WHERE u.username!='admin'");
        $users = $query->getResult();
        $this->assertEquals(1, count($users));
    }

}

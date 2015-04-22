<?php

namespace AppBundle\Utils\Test;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;

/**
 * This trait gives 
 * extend from required
 *
 * @author dkociuba
 */
trait DatabaseHelper {

    private $entityMenagerDatabaseHelper;
    private $applicationDatabaseHelper;

    public function dropDatabase() {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->applicationDatabaseHelper = new Application(static::$kernel);

        // drop the database
        $command = new DropDatabaseDoctrineCommand();
        $this->applicationDatabaseHelper->add($command);
        $input = new ArrayInput(array(
            'command' => 'doctrine:database:drop',
            '--force' => true
        ));
        $command->run($input, new NullOutput());

        // we have to close the connection after dropping the database so we don't get "No database selected" error
        $connection = $this->applicationDatabaseHelper->getKernel()->getContainer()->get('doctrine')->getConnection();
        if ($connection->isConnected()) {
            $connection->close();
        }
    }

    public function createDatabase() {
        // create the database
        $command = new CreateDatabaseDoctrineCommand();
        $this->applicationDatabaseHelper->add($command);
        $input = new ArrayInput(array(
            'command' => 'doctrine:database:create',
        ));
        $command->run($input, new NullOutput());
    }

    public function createSchema() {
        // create schema
        $command = new CreateSchemaDoctrineCommand();
        $this->applicationDatabaseHelper->add($command);
        $input = new ArrayInput(array(
            'command' => 'doctrine:schema:create',
        ));
        $command->run($input, new NullOutput());
    }

    public function loadFixtures() {
        // get the Entity Manager
        $this->entityMenagerDatabaseHelper = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();

        // load fixtures
        $client = static::createClient();
        $loader = new \Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader($client->getContainer());
        $loader->loadFromDirectory(static::$kernel->locateResource('@AppBundle/DataFixtures/ORM'));
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->entityMenagerDatabaseHelper);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->entityMenagerDatabaseHelper, $purger);
        $executor->execute($loader->getFixtures());
    }

}

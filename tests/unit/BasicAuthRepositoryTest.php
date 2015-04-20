<?php

namespace Reservat\Test;

use \Reservat\Core\Config;
use Aura\Di\Container;
use Aura\Di\Factory;

class BasicAuthRepositoryTest extends \PHPUnit_Framework_TestCase
{

    protected $manager = null;

    public function setUp()
    {

        // Schema
        $schema =<<<SQL
        CREATE TABLE "admin" (
        "id" INTEGER PRIMARY KEY,
        "username" VARCHAR NOT NULL,
        "password" VARCHAR NOT NULL,
        "email" VARCHAR NOT NULL
        );
SQL;

        $this->di = new Container(new Factory);
        $this->di->set('db', function () {
            return new \PDO('sqlite::memory:');
        });

        $this->di->get('db')->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->di->get('db')->exec($schema);


        require_once(__DIR__.'/_files/Admin.php');
        require_once(__DIR__.'/_files/AdminRepository.php');
        require_once(__DIR__.'/_files/AdminDataMapper.php');
        require_once(__DIR__.'/_files/AdminManager.php');

        // Dependencies
        $this->manager = new \Reservat\Manager\AdminManager($this->di);

        $admins = [
            [
                'username' => 'abc',
                'email' => 'paul@example.com',
                'password' => \Reservat\Admin::hash('test')
            ],
            [
                'username' => 'test1',
                'email' => 'paul2@example.com',
                'password' => \Reservat\Admin::hash(':poop:')
            ],
            [
                'username' => 'test2',
                'email' => 'paul3@example.com',
                'password' => \Reservat\Admin::hash('cake')
            ],
        ];

        foreach ($admins as $admin) {
            $admin = \Reservat\Admin::create($admin, $this->manager->getRepository());
            $this->manager->getDatamapper()->insert($admin);
        }

    }

    public function testGetByUsername()
    {
        $res = $this->manager->getRepository()->getByAuthIdentifiers('test1');
        $this->assertEquals('paul2@example.com', $res->current()['email']);
    }

    public function testGetByEmail()
    {
        $res = $this->manager->getRepository()->getByAuthIdentifiers('paul@example.com');
        $this->assertEquals('abc', $res->current()['username']);
    }
}

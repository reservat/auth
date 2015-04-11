<?php

namespace Reservat\Test;

use Reservat\Core\Config;
use Aura\Di\Container;
use Aura\Di\Factory;

class AuthGateKeeperTest extends \PHPUnit_Framework_TestCase
{

    protected $di = null;

    public function setUp()
    {
        require_once(__DIR__.'/_files/Admin.php');
        require_once(__DIR__.'/_files/AdminRepository.php');
        require_once(__DIR__.'/_files/AdminDataMapper.php');

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

        // Dependencies
        $this->mapper = new \Reservat\Datamapper\AdminDatamapper($this->di->get('db'));

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
            $admin = \Reservat\Admin::create($admin);
            $this->mapper->insert($admin);
        }

    }

    public function testBasicEntity()
    {
        $data = ['username' => 'abc', 'password' => 'test'];
        $gateway = new \Reservat\Auth\GateKeeper($this->di, 'Admin', 'Basic');
        $result = $gateway->login($data);

        $this->assertEquals($result['Basic']->getUsername(), 'abc');
    }
}

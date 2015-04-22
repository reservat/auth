<?php

namespace Reservat\Test;

use Reservat\Core\Config;
use Aura\Di\Container;
use Aura\Di\Factory;
use Reservat\Session\Session;

class AuthGateKeeperTest extends \PHPUnit_Framework_TestCase
{

    protected $di = null;
    protected $manager = null;

    public function __construct()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        ob_start();
    }

    public function setUp()
    {
        require_once(__DIR__.'/_files/Admin.php');
        require_once(__DIR__.'/_files/AdminRepository.php');
        require_once(__DIR__.'/_files/AdminDatamapper.php');
        require_once(__DIR__.'/_files/AdminManager.php');

        // Schema
        $userSchema =<<<SQL
        CREATE TABLE "admin" (
        "id" INTEGER PRIMARY KEY,
        "username" VARCHAR NOT NULL,
        "password" VARCHAR NOT NULL,
        "email" VARCHAR NOT NULL,
        "api_key" VARCHAR NOT NULL
        );
SQL;

        $sessionSchema =<<<SQL
        CREATE TABLE "session" (
        "id" INTEGER PRIMARY KEY,
        "session_id" VARCHAR NOT NULL,
        "user_id" INT,
        "data" TEXT,
        "expires" INT
        );
SQL;

        $this->di = new Container(new Factory);

        $this->di->set('db', function () {
            return new \PDO('sqlite::memory:');
        });

        $this->di->set('session', function () {
            return new Session($this->di, 'PDO');
        });

        $this->di->get('db')->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->di->get('db')->exec($userSchema);
        $this->di->get('db')->exec($sessionSchema);

        $testSessionData = [
            1,
            'k4o6898jdru8e8gah9mkv5fss5',
            1,
            'userId|i:1;',
            (new \DateTime())->getTimeStamp() + 1000
        ];

        $sql = "INSERT INTO session (id, session_id, user_id, data, expires) VALUES ('". implode("','", $testSessionData) ."')";
        $this->di->get('db')->exec($sql);

        // Dependencies
        $this->manager = new \Reservat\Manager\AdminManager($this->di);

        $admins = [
            [
                'username' => 'abc',
                'email' => 'paul@example.com',
                'password' => \Reservat\Admin::hash('test'),
                'api_key' => '123456789'
            ],
            [
                'username' => 'test1',
                'email' => 'paul2@example.com',
                'password' => \Reservat\Admin::hash(':poop:'),
                'api_key' => '085632241'
            ],
            [
                'username' => 'test2',
                'email' => 'paul3@example.com',
                'password' => \Reservat\Admin::hash('cake'),
                'api_key' => '987654321'
            ],
        ];

        foreach ($admins as $admin) {
            $admin = \Reservat\Admin::createFromArray($admin);
            $this->manager->getDatamapper()->insert($admin);
        }

    }

    public function testGateKeeperLoginBasic()
    {
        $data = ['username' => 'abc', 'password' => 'test'];

        $gateway = new \Reservat\Auth\GateKeeper($this->di, $this->manager, 'Basic');

        $result = $gateway->login($data);

        $basicUser = $result['Basic']->getUser();

        $this->assertEquals($basicUser->getUsername(), 'abc');

        $result2 = $gateway->login(['username' => 'foobar', 'password' => 'idontexist']);

        $this->assertEquals($result2['Basic'], false);

    }

    public function testGateKeeperCheckBasic()
    {
        /* Set our stored DB session MANUALLY for testing */
        session_id('k4o6898jdru8e8gah9mkv5fss5');
        $handler = $this->di->get('session')->getHandler();
        session_decode($handler->read('k4o6898jdru8e8gah9mkv5fss5'));

        $gateway = new \Reservat\Auth\GateKeeper($this->di, $this->manager, 'Basic');

        $result = $gateway->check();

        if ($result['Basic']) {
            $user = $result['Basic']->getUser();
            $this->assertEquals($user->getUsername(), 'abc');
        }
    }

    public function testGateKeeperLoginAuth()
    {
        $data = ['apiKey' => '123456789'];

        $gateway = new \Reservat\Auth\GateKeeper($this->di, $this->manager, 'API');

        $result = $gateway->login($data);

        $user = $result['API']->getUser();
        $this->assertEquals($user->getUsername(), 'abc');

    }
}

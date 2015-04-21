<?php

namespace Reservat\Test;

use \Reservat\Core\Config;
use Aura\Di\Container;
use Aura\Di\Factory;

class BasicAuthEntityTest extends \PHPUnit_Framework_TestCase
{

    protected $manager = null;

    public function setUp()
    {
        require_once(__DIR__.'/_files/Admin.php');
        require_once(__DIR__.'/_files/AdminRepository.php');
        require_once(__DIR__.'/_files/AdminDataMapper.php');
        require_once(__DIR__.'/_files/AdminManager.php');

        $this->di = new Container(new Factory);
        $this->di->set('db', function () {
            return new \PDO('sqlite::memory:');
        });

        $this->manager = new \Reservat\Manager\AdminManager($this->di);
    }

    public function testBasicEntity()
    {
        $admin = $this->manager->getEntity([
            'username' => 'PWesterdale',
            'email' => 'paul@westerdale.me',
            'password' => \Reservat\Admin::hash('cake'),
            'api_key' => '012345678'
        ]);

        $this->assertEquals($admin->verify('cake'), true);
    }
}

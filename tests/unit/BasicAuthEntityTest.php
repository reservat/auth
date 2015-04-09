<?php

namespace Reservat\Test;

use \Reservat\Core\Config;

class BasicAuthEntityTest extends \PHPUnit_Framework_TestCase
{
    protected $admin = null;

    public function setUp()
    {
        require_once(__DIR__.'/_files/Admin.php');
    }

    public function testBasicEntity()
    {
        $admin = \Reservat\Test\Admin::create([
            'username' => 'PWesterdale',
            'email' => 'paul@westerdale.me',
            'password' => 'cake'
        ]);

        $this->assertEquals($admin->verify('cake'), true);
    }


}
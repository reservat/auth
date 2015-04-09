<?php

namespace Reservat\Test;

use \Reservat\Core\Config;

class BasicAuthRepositoryTest extends \PHPUnit_Framework_TestCase
{
    protected $admin = null;

    protected $pdo = null;

    protected $mapper = null;

    protected $repo = null;

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

        // DB
        $this->pdo = new \PDO('sqlite::memory:');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec($schema);

        require_once(__DIR__.'/_files/Admin.php');
        require_once(__DIR__.'/_files/AdminRepository.php');
        require_once(__DIR__.'/_files/AdminDataMapper.php');

        // Dependencies
        $this->mapper = new \Reservat\Datamapper\AdminDatamapper($this->pdo);
        $this->repo = new \Reservat\Repository\AdminRepository($this->pdo);

        $admins = [
            [
                'username' => 'abc',
                'email' => 'paul@example.com',
                'password' => 'test'                
            ],
            [
                'username' => 'test1',
                'email' => 'paul2@example.com',
                'password' => ':poop:'                
            ],
            [
                'username' => 'test2',
                'email' => 'paul3@example.com',
                'password' => 'cake'                
            ],
        ];

        foreach($admins as $admin){
            $admin = \Reservat\Test\Admin::create($admin);
            $this->mapper->insert($admin);
        }

    }

    public function testGetByUsername()
    {
        $res = $this->repo->getByAuthIdentifiers('test1');
        $this->assertEquals('paul2@example.com', $res->current()['email']);
    }

    public function testGetByEmail()
    {
        $res = $this->repo->getByAuthIdentifiers('paul@example.com');
        $this->assertEquals('abc', $res->current()['username']);
    }


}
<?php

namespace Reservat\Auth\Drivers;

use Reservat\Auth\Interfaces\DriverInterface;
use Reservat\Core\Interfaces\EntityInterface;

class Basic implements DriverInterface
{

    protected $entity;
    protected $repo;

    public function __construct($di, $entityName)
    {
        $repoClass = '\Reservat\Repository\\' . $entityName . 'Repository';
        $this->repo = new $repoClass($di->get('db'));
    }

    public function login(array $data)
    {
        if (!$data['username']) {
            throw new \InvalidArgumentException('You must provide a username to '. get_class . ' check function');
        }
        if (!$data['password']) {
            throw new \InvalidArgumentException('You must provide a password to '. get_class . ' check function');
        }

        $this->repo->getByAuthIdentifiers($data['username']);

        $user = $this->repo->getResults('Admin');

        return $user->verify($data['password']) ? $user : false;

    }

    public function setEntity(EntityInterface $entity)
    {

    }
}

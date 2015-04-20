<?php

namespace Reservat\Auth\Drivers;

use Reservat\Auth\Interfaces\DriverInterface;
use Reservat\Core\Interfaces\EntityInterface;

class Basic implements DriverInterface
{

    protected $repo = null;
    protected $di = null;
    protected $user = false;
    protected $manager = null;

    public function __construct($di, $manager)
    {
        $this->di = $di;
        $this->manager = $manager;
        $this->repo = $manager->getRepository();
    }

    public function login(array $data)
    {
        if (!$data['username']) {
            throw new \InvalidArgumentException('You must provide a username to '. get_class() . ' check function');
        }
        if (!$data['password']) {
            throw new \InvalidArgumentException('You must provide a password to '. get_class() . ' check function');
        }

        $this->repo->getByAuthIdentifiers($data['username']);

        $user = $this->repo->getResults($this->manager->getEntity());

        if (!$user) {
            return false;
        }

        $loggedIn = $user->verify($data['password']);

        if (!$loggedIn) {
            return false;
        }

        $this->user = $user;
        $this->di->get('session')->set('userId', $this->user->getId());

        return $this;

    }

    public function check(array $data = array())
    {
        $userId = $this->di->get('session')->get('userId');

        if (!$userId) {
            return false;
        }

        $this->user = $this->repo->getById($userId)->getResults($this->manager->getEntity());

        return $this;

    }

    public function getUser()
    {
        return $this->user;
    }
}

<?php

namespace Reservat\Auth\Drivers;

use Reservat\Auth\Interfaces\DriverInterface;
use Reservat\Core\Interfaces\EntityInterface;

class Api implements DriverInterface
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
        return $this->check($data);
    }

    public function check(array $data = array())
    {
        if (!$data['apiKey']) {
            throw new \InvalidArgumentException('You must provide an API key to '. get_class() . ' login/check function');
        }

        $this->user = $this->repo->getByAPIKey($data['apiKey'])->getResults($this->manager->getEntity());

        return $this;

    }

    public function getUser()
    {
        return $this->user;
    }
}

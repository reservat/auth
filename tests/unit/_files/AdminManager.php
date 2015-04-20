<?php

namespace Reservat\Manager;

use Reservat\Core\Interfaces\ManagerInterface;
use Reservat\Repository\AdminRepository;
use Reservat\Datamapper\AdminDatamapper;
use Reservat\Admin;

class AdminManager implements ManagerInterface
{
    public function __construct($di)
    {
        $this->repository = new AdminRepository($di->get('db'));
        $this->datamapper = new AdminDatamapper($di->get('db'));
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getDatamapper()
    {
        return $this->datamapper;
    }

    public function getEntity(...$args)
    {
        return new Admin(...$args);
    }
}

<?php

namespace Reservat\Repository;

use Reservat\Auth\Repository\BasicPDORepository;
use Reservat\Auth\Interfaces\BasicPDORepositoryInterface;

class AdminRepository extends BasicPDORepository implements BasicPDORepositoryInterface
{
    /**
     * Return a the table name.
     *
     * @return string
     */
    public function table()
    {
        return 'admin';
    }

    public function identifiers()
    {
        return ['username', 'email'];
    }
}

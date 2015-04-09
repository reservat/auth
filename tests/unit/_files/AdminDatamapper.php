<?php

namespace Reservat\Datamapper;

use Reservat\Core\Interfaces\SQLDatamapperInterface;
use Reservat\Core\Datamapper\PDODatamapper;

class AdminDatamapper extends PDODatamapper implements SQLDatamapperInterface
{
    /**
     * Return the table name we're interacting with.
     *
     * @return string
     */
    public function table()
    {
        return 'admin';
    }
}

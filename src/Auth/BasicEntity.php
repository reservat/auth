<?php

namespace Reservat\Auth;

use Reservat\Auth\Interfaces\BasicEntityInterface;
use Reservat\Core\Entity;

abstract class BasicEntity extends Entity implements BasicEntityInterface
{

    protected $password = null;

    public static function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function setHashedPassword($password)
    {
        $this->password = static::hash($password);
    }

    public function verify($value)
    {
        return password_verify($value, $this->password);
    }
}

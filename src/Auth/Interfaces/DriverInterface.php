<?php

namespace Reservat\Auth\Interfaces;

interface DriverInterface
{
    public function login(array $data);
}

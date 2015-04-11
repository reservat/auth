<?php

namespace Reservat\Auth;

class GateKeeper
{
   

    protected $activeDrivers = [];

    public function __construct($di, $entityName, $driver)
    {

        if (!in_array('Reservat\Auth\Interfaces\\' . $driver . 'EntityInterface', class_implements('\Reservat\\' . $entityName))) {
            throw new \InvalidArgumentException('Entity '. $entityName . ' does not implement its associated entity interface');
        }
        if (!class_exists('\Reservat\Auth\Drivers\\' . $driver)) {
            throw new \InvalidDriverException('Driver ' . $driver . ' does not exist');
        }

        if (!isset($this->activeDrivers[$driver])) {
            $driver = '\Reservat\Auth\Drivers\\'.$driver;
            $this->activeDrivers[$driver] = new $driver($di, $entityName);
        }

    }

    public function login($data)
    {
        $results = [];
        foreach ($this->activeDrivers as $driver) {
            $results[(new \ReflectionClass($driver))->getShortName()] = $driver->login($data);
        }
        return $results;
    }
}

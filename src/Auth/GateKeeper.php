<?php

namespace Reservat\Auth;

class GateKeeper
{
   
    protected $activeDrivers = [];

    public function __construct($di, $manager, $driver)
    {
        
        $driverClass = '\Reservat\Auth\Drivers\\'.$driver;

        if (!class_exists($driverClass)) {
            throw new \InvalidDriverException('Driver ' . $driver . ' does not exist');
        }

        if (!isset($this->activeDrivers[$driver])) {
            $this->activeDrivers[$driver] = new $driverClass($di, $manager);
        }

    }

    public function getDriver($driverName)
    {
        return $this->activeDrivers[$driverName];
    }

    public function login($data)
    {
        $results = [];

        foreach ($this->activeDrivers as $key => $driver) {
            $results[$key] = $driver->login($data);
        }

        return $results;

    }

    public function check($data = array())
    {
        $results = [];

        foreach ($this->activeDrivers as $key => $driver) {
            $results[$key] = $driver->check($data);
        }

        return $results;
    }
}

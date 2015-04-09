<?php

namespace Reservat\Auth;

class GateKeeper
{	

	protected $entity;

	protected $activeDrivers = [];

	public function __construct($entity, $driver, $data){

		if (!in_array('Reservat\Auth\Interfaces\\' . $driver . 'EntityInterface' , class_implements($entity))) {
			throw new \InvalidArgumentException('Entity '. $entity . ' does not implement its associated entity interface');
		}
		if (!class_exists('\Reservat\Auth\Drivers\\' . $driver)) {
			throw new \InvalidDriverException('Driver ' . $driver . ' does not exist');
		}

		$this->entity = $entity;

		if(!$this->activeDrivers[$driver]){
			
		}

	}

}
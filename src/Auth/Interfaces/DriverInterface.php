<?php

namespace Reservat\Auth\Interfaces;

interface DriverInterface
{
	public function check($data);
}
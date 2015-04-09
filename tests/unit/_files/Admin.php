<?php

namespace Reservat\Test;

class Admin extends \Reservat\Auth\BasicEntity implements \Reservat\Core\Interfaces\EntityInterface
{
	
	protected $username;
	protected $email;
	protected $password;

	public function toArray()
	{
		return [
			'username' => $this->username,
			'email' => $this->email,
			'password' => $this->password
		];
	}

}
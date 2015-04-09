<?php

namespace Reservat\Auth;

use Reservat\Auth\Interfaces\BasicEntityInterface;
use Reservat\Core\Entity;

class BasicEntity extends Entity implements BasicEntityInterface
{

	protected $password;

	public static function create($data)
    {
        $entity = new static();

        foreach ($data as $key => $value) {
            if (property_exists($entity, $key)) {
            	$entity->$key = $key == 'password' ? static::hash($value) : $value;
            } else {
                throw new \InvalidArgumentException('Property ' . $key . ' does not exist on Entity ' . get_class($entity));
            }
        }

        return $entity;

    }

    private static function hash($password)
    {
    	return password_hash($password, PASSWORD_DEFAULT);
    }

	public function setPassword($password)
	{
		$this->password = $this->hash($password);
	}

	public function verify($value)
	{
		return password_verify($value, $this->password);
	}

}
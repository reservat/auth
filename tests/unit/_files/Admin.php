<?php

namespace Reservat;

class Admin extends \Reservat\Auth\BasicEntity implements \Reservat\Core\Interfaces\EntityInterface
{
    
    protected $username;
    protected $email;
    protected $password;
    protected $apiKey;

    public function __construct($username, $email, $password, $apiKey)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->apiKey = $apiKey;
    }

    public static function createFromArray(array $data)
    {
        return new static($data['username'], $data['email'], $data['password'], $data['api_key']);
    }

    public function toArray()
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'api_key' => $this->apiKey
        ];
    }

    public function getUsername()
    {
        return $this->username;
    }
}

<?php

namespace Reservat;

class Admin extends \Reservat\Auth\BasicEntity implements \Reservat\Core\Interfaces\EntityInterface
{
    
    protected $username;
    protected $email;
    protected $password;
    protected $apiKey;

    public function __construct(...$params)
    {
        $this->username = isset($params[0]) ? $params[0] : null;
        $this->email = isset($params[1]) ? $params[1] : null;
        $this->password = isset($params[2]) ? $params[2] : null;
        $this->apiKey = isset($params[3]) ? $params[3] : null;
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

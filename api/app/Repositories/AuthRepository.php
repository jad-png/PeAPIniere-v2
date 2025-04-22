<?php

namespace App\Repositories;

use App\DAOs\AuthDAO;

class AuthRepository
{
    protected AuthDAO $authDAO;

    public function __construct(AuthDAO $authDAO)
    {
        $this->authDAO = $authDAO;
    }

    public function register($credentials)
    {
        return $this->authDAO->register($credentials);
    }

    public function login($credentials)
    {
        return $this->authDAO->login($credentials);
    }

    public function logout()
    {
        return $this->authDAO->logout();
    }

    public function profile($credentials)
    {
        return $this->authDAO->profile($credentials);
    }
}

<?php

namespace App\DAOs\Interfaces;

interface AuthInterface
{
    public function register($credentials);
    public function login($credentials);
    public function logout();
    public function profile($credentials);
}

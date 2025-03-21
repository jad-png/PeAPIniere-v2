<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    protected $key;
    protected $alg;

    public function __construct()
    {
        $this->key = env("JWT_KEY");
        $this->alg = "HS256";
    }

    public function generateToken($payload)
    {
        return JWT::encode($payload, $this->key, $this->alg);
    }
    
    public function decodeToken($token)
    {
        return JWT::decode($token, new Key($this->key, $this->alg));
    }
}
<?php

namespace App\DAOs;

use App\DAOs\Interfaces\AuthInterface;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthDAO implements AuthInterface
{
    protected User $user;
    protected JwtService $jwtService;

    public function __construct(JwtService $jwtService, User $user)
    {
        $this->jwtService = $jwtService;
        $this->user = $user;
    }

    public function register($credentials)
    {
        $user = $this->user->create([
            "fullname" => $credentials["fullname"],
            "email" => $credentials["email"],
            "password" => $credentials["password"],
        ]);

        return $user;
    }

    public function login($credentials)
    {
        $user = User::where('email', $credentials["email"])->first();
        if (!$user || !Hash::check($credentials["password"], $user->password)) return null;

        $payload = [
            'sub' => $user->id,
            'role' => $user->role_id,
            'iat' => now()->timestamp,
            'exp' => now()->addMinutes(30)->timestamp
        ];
        $token = $this->jwtService->generateToken($payload);
        $cookie = Cookie::make("jwttoken", $token, 60);

        return ["cookie" => $cookie, "token" => $token];
    }

    public function logout()
    {
        // 
    }

    public function profile($credentials)
    {
        $payload = $credentials->attributes->get("jwt_payload");
        if (!$payload || !$payload->sub) return null;

        $user = $this->user->find($payload->sub);
        $data = [
            "user" => $user
        ];

        return $data;
    }
}

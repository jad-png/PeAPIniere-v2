<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function register(Request $request)
    {
        $fields = $request->validate([
            "fullname" => ['required', 'max:100'],
            "email" => ['required', 'email', 'max:255', 'unique:users'],
            "password" => ['required', 'confirmed']
        ]);

        $user = User::create($fields);
        return $this->sendResponse('Registred succesfully', $user, 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => ['required'],
            "password" => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError("Email or password incorrect");
        }

        $payload = [
            'sub' => $user->id,
            'role' => $user->role_id,
            'iat' => now()->timestamp,
            'exp' => now()->addMinutes(30)->timestamp
        ];
        $token = $this->jwtService->generateToken($payload);
        $cookie = Cookie::make("jwttoken", $token, 60);

        return $this->sendResponse("Logged in succesfully.", ["token" => $token])->cookie($cookie);
    }

    public function logout(Request $request)
    {
        return response()->json(['message' => "You are logged out."], 200)->cookie("jwttoken", '', 0);
    }

    public function profile(Request $request)
    {
        $payload = $request->attributes->get("jwt_payload");

        if (!$payload || !$payload->sub)
            return $this->sendError("Unauthorized", [], 401);

        $user = User::find($payload->sub);;

        $data = [
            "user" => $user
        ];

        return $this->sendResponse("", $data);
    }
}

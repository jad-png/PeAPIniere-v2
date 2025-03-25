<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(Request $request)
    {
        try {
            $fields = $request->validate([
                "fullname" => ['required', 'max:100'],
                "email" => ['required', 'email', 'max:255', 'unique:users'],
                "password" => ['required', 'confirmed']
            ]);
        } catch (ValidationException $e) {
            return $this->sendError("Validation errors", $e->errors(), 422);
        }

        $user = $this->authRepository->register($fields);

        if (!$user) return $this->sendError("Registration failed.");
        else return $this->sendResponse('Registred succesfully.', $user, 201);
    }

    public function login(Request $request)
    {
        try {
            $fields = $request->validate([
                "email" => ['required'],
                "password" => ['required'],
            ]);
        } catch (ValidationException $e) {
            return $this->sendError("Validation errors", $e->errors(), 422);
        }

        $user = $this->authRepository->login($fields);
        if (!$user) return $this->sendError("Credentials incorrects.");
        if ($user["token"] && $user["cookie"])
            return $this->sendResponse("Logged in succefully.", $user["token"])->cookie($user["cookie"]);
    }

    public function logout()
    {
        return $this->sendResponse("Logged out.", [], 200)->cookie("jwttoken", "", 0);
    }

    public function profile(Request $request)
    {
        $user = $this->authRepository->profile($request);
        return $this->sendResponse("Profile details.", $user);
    }
}

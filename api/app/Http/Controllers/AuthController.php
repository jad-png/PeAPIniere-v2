<?php

namespace App\Http\Controllers;

use App\DTOs\LoginDTO;
use App\DTOs\RegisterDTO;
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
            $dto = RegisterDTO::fromRequest($request->all());
            $user = $this->authRepository->register($dto->toArray());

            if (!$user) return $this->sendError("Registration failed.");
            else return $this->sendResponse('Registred succesfully.', $user, 201);
        } catch (ValidationException $e) {
            return $this->sendError("Validation errors", $e->errors(), 422);
        }
    }

    public function login(Request $request)
    {
        try {
            $dto = LoginDTO::fromRequest($request->all());
            $user = $this->authRepository->login($dto->toArray());

            if (!$user) return $this->sendError("Credentials incorrects.");

            if ($user["token"] && $user["cookie"]) {
                return $this->sendResponse("Logged in successfully.", $user["token"])
                    ->cookie($user["cookie"]);
            }
        } catch (ValidationException $e) {
            return $this->sendError("Validation errors", $e->errors(), 422);
        }
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

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

    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"User"},
     *     description="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "phone", "email", "password", "password_confirmation"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="John Doe"
     *             ),
     *             @OA\Property(
     *                 property="phone",
     *                 type="string",
     *                 example="1234567890"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="john.doe@example.com"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="password"
     *             ),
     *             @OA\Property(
     *                 property="password_confirmation",
     *                 type="string",
     *                 format="password",
     *                 example="password"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="user",
     *                 ref="#/components/schemas/User"
     *             ),
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 example="1|abcdef123456"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"User"},
     *     description="Login an existing user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="john.doe@example.com"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="password"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="user",
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Email or password are incorrect"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/logout",
     *     tags={"User"},
     *     description="Logout the authenticated user",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="You are logged out."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function logout()
    {
        return $this->sendResponse("Logged out.", [], 200)->cookie("jwttoken", "", 0);
    }

    /**
     * @OA\Get(
     *     path="/profile",
     *     tags={"User"},
     *     description="Get the details of the authenticated user",
     *     @OA\Response(
     *         response=200,
     *         description="User displayed successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="user",
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function profile(Request $request)
    {
        $user = $this->authRepository->profile($request);
        return $this->sendResponse("Profile details.", $user);
    }
}

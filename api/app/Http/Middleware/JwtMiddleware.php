<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = Cookie::get("jwttoken");

        if (!$token) {
            return response()->json(["message" => "Token not provided"], 401);
        }

        try {
            $decoded = $this->jwtService->decodeToken($token);
            $request->attributes->add(["jwt_payload" => $decoded]);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Token invalid or expired",
                "exception" => $e->getMessage()
            ], 401);
        }
        return $next($request);
    }
}

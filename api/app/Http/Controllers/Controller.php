<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[
    OA\Info(version: "1.0.0", description: "PeAPIniere api documentation", title: "PeAPIniere"),
    OA\Server(url: "http://127.0.0.1:8000/api", description: "local server"),
    OA\SecurityScheme(securityScheme: "bearerAuth", type: "http", name: "Authorization", in: "header", scheme: "bearer"),
]
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($message, $data, $code = 200)
    {
        $response = [
            "success" => true,
            "data" => $data,
            "message" => $message
        ];

        return response()->json($response, $code);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            "success" => false,
            "message" => $error,
            "data" => $errorMessages
        ];

        return response()->json($response, $code);
    }
}

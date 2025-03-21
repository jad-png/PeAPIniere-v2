<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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

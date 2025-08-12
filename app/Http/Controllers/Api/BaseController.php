<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class BaseController extends Controller
{
    public function sendResponse($result = [], string $message = 'Operation successful', int $code = 200, array $headers = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ], $code, $headers);
    }

    public function sendError(string $error, array $errorMessages = [], int $code = 400, array $headers = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code, $headers);
    }
}

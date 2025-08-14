<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as LaravelController;
use Illuminate\Http\JsonResponse;
class BaseController extends LaravelController
{
    protected function sendResponse(mixed $result = [], string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $result,
        ], $code);
    }

    protected function sendError(string $errorMessage = 'Error', int $code = 400, mixed $errors = null): JsonResponse
    {
        $response = [
            'status'  => 'error',
            'message' => $errorMessage,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}

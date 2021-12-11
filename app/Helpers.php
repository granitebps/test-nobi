<?php

namespace App;

use Illuminate\Http\JsonResponse;

class Helpers
{
    public static function successResponse(
        string $message = '',
        mixed $result = [],
        int $code = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $result,
        ], $code);
    }

    public static function errorResponse(
        string $message = '',
        int $code = 400
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}

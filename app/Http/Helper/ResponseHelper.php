<?php

namespace App\Http\Helper;

use Illuminate\Http\JsonResponse;

class ResponseHelper {
    
    public static function success(string $message = "تمت العملية بنجاح", mixed $data = null, int $code = 200): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'code'    => $code
        ], $code);
    }

    public static function fail(string $message = "فشلت العملية", mixed $data = null, int $code = 400): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $data,
            'code'    => $code
        ], $code);
    }
}
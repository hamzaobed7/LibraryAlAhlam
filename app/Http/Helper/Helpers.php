<?php

use App\Http\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;

function apiSuccess(string $message = "تمت العملية بنجاح", mixed $data = null, int $code = 200) : JsonResponse {
    return ResponseHelper::success($message, $data, $code);
}

function apiFail(string $message = "فشلت العملية", mixed $data = null, int $code = 400) : JsonResponse {
    return ResponseHelper::fail($message, $data, $code);
}
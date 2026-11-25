<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function success(
        mixed $data = null,
        string $message = 'Berhasil.',
        array $meta = [],
        array $links = [],
        int $status = 200,
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => (object) $meta,
            'links' => (object) $links,
        ], $status);
    }
}
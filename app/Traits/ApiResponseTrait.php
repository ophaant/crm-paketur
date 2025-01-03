<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function success(array $data = null, int $code = 200, array $message = null): JsonResponse
    {
        $response = $message ?? config('rc.successfully');
        $response['data'] = $data;

        return response()->json($response, $code);
    }

    public function error($error, $code = 404)
    {
        unset($error['data']);
        $response = $error;

        return response()->json($response, $code);
    }
}
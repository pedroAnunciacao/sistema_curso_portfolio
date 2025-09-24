<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class FormatJsonResponse
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $original = $response->getData(true);
            $status = $response->status();
            if ($status < 400) {
                $wrapped = [
                    'status'  => 'success',
                    'message' => 'Request processed successfully.',
                    'data'    => $original['data'] ?? $original,
                    'code'    => $status,
                ];
            } else {
                Log::error('API Error', [
                    'status'   => $status,
                    'original' => $original,
                ]);
                $wrapped = [
                    'status'  => 'error',
                    'message' => 'An error occurred while processing your request.',
                    'data'    => null,
                    'errors'  => $original['errors'] ?? null, // mantém se existir
                    'code'    => $status,
                ];
            }

            return response()->json($wrapped, $status);
        }

        return $response;
    }
}

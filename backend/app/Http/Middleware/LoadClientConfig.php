<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LoadClientConfig
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && $request->client_id) {
            // Injeta o client_id obtido do BindRequestFilter
            app('client')->loadConfig($request->client_id);
        }

        return $next($request);
    }
}

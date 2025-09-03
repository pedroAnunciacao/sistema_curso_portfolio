<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LoadClientConfig
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $client = app('client');
            $client->loadConfig(auth()->user()->pessoa_id);
        }

        return $next($request);
    }
}

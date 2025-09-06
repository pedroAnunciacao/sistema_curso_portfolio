<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class BindRequestFilter
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('X-Content')) {
            $user = User::with('person')->find($request->header('X-Content'));

            if ($user) {
                $request->merge($user->resolveRoleIds());
            }
        }

        return $next($request);
    }
}

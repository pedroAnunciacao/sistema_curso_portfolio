<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BindPessoaId
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('X-Content')) {
            $request->merge([
                'pessoaId' => $request->header('X-Content')
            ]);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class BlockDirectHttpMethodAccess
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}

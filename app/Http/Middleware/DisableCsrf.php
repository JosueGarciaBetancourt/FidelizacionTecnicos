<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableCsrf
{
    public function handle($request, Closure $next)
    {
        // Deshabilitar CSRF para las solicitudes a la API
        if ($request->is('loginmovil/login-tecnico')) {
            $request->session()->forget('_token');
        }

        return $next($request);
    }
}
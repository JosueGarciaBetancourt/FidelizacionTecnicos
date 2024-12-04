<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next)
    {
        // Obtener la API Key del encabezado
        $apiKey = $request->header('Authorization');

        if (!$apiKey) {
            return response()->json(['message' => 'API Key requerida'], 401);
        }

        // Verificar si la API Key existe en la base de datos
        $tecnico = DB::table('login_tecnicos')
            ->where('api_key', $apiKey)
            ->first();

        if (!$tecnico) {
            return response()->json(['message' => 'API Key no válida'], 401);
        }

        // Si la API Key es válida, continuar con la solicitud
        return $next($request);
    }
}

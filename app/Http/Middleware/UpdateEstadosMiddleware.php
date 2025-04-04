<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\VentaIntermediadaController;
use App\Http\Controllers\SolicitudCanjeController;

class UpdateEstadosMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Solo ejecutar para solicitudes de página principal, no para assets o AJAX
        if (!$request->ajax() && $this->isHtmlRequest($request) && $request->method() === 'GET') {
            $lockFile = storage_path('framework/cache/estado_update_lock');
            $lockExpiration = 10; // segundos
            
            $canRun = true;
            
            if (file_exists($lockFile)) {
                $fileTime = filemtime($lockFile);
                // Si el archivo de bloqueo existe y tiene menos de N segundos, no ejecutar
                if (time() - $fileTime < $lockExpiration) {
                    $canRun = false;
                }
            }
            
            if ($canRun) {
                //Log::info("Ejecutando actualización de estados...");
                
                // Crear o actualizar el archivo de bloqueo
                file_put_contents($lockFile, time());
                
                try {
                    VentaIntermediadaController::updateEstadosVentasIntermediadasMaxDayCanje();
                    SolicitudCanjeController::updateEstadosSolicitudCanjeMaxDayCanje();
                } catch (\Exception $e) {
                    Log::error("Error al actualizar estados: " . $e->getMessage());
                }
            }
        }
        
        return $next($request);
    }
    
    /**
     * Determina si la solicitud es probablemente para una página HTML principal
     */
    private function isHtmlRequest(Request $request): bool
    {
        // Comprobar el encabezado Accept
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader && stripos($acceptHeader, 'text/html') !== false) {
            // Verificar que no sea una solicitud para un archivo estático
            $path = $request->path();
            $staticExtensions = ['js', 'css', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'];
            
            foreach ($staticExtensions as $ext) {
                if (str_ends_with($path, '.' . $ext)) {
                    return false;
                }
            }
            
            return true;
        }
        
        return false;
    }
}
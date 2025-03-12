<?php

namespace App\Http\Controllers;

use App\Events\NotificationReviewed;
use Illuminate\Http\Request;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class SystemNotificationController extends Controller
{
    public static function getActiveNotifications()
    {
        $notifications = SystemNotification::where('active', true)
            ->orderBy('created_at', 'desc')
            //->take(5)
            ->get();
            
        return $notifications;
        /* return response()->json([
            'notifications' => $notifications,
            'notiCount' => $notifications->count()
        ]); */
    }
    
    // Método para usar en vistas con Blade
    public function getNotificationsForView()
    {
        $notifications = SystemNotification::where('active', true)
            ->orderBy('created_at', 'desc')
            //->take(5)
            ->get();
            
        return view('components.system-notifications', compact('notifications'));
    }
    
    public function deactivateNotification(Request $request)
    {   
        try {
            $idNotificacion = $request->input('idNotificacion', '');
            $routeToReview = $request->input('routeToReview', '');

            if ($idNotificacion && $routeToReview) {
                $notification = SystemNotification::findOrFail($idNotificacion);
                $notification->active = 0;
                $notification->save();

                /* Log::info("Datos recibidos en deactivateNotification", [
                    'idNotificacion' => $idNotificacion,
                    'routeToReview' => $routeToReview
                ]); */
                
                // Disparar el evento para eliminar la notificación en la BD
                event(new NotificationReviewed($idNotificacion));

                if (Route::has($routeToReview)) { 
                    return response()->json([
                        'status' => 'success',
                        'redirect_url' => route($routeToReview)
                    ]);
                } else {
                    Log::warning("Intento de redirección a una ruta inexistente: $routeToReview");
                    return response()->json(['status' => 'failure']);
                }
            }

            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Error en deactivateNotification: ' . $e->getMessage());
            return response()->json(['status' => 'failure']);
        }
    }
}
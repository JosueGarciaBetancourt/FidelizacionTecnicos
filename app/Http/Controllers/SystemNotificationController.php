<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Log;

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
    
    // Este método podría usarse por un administrador para desactivar notificaciones
    public function deactivateNotification(Request $request)
    {   
        try {
            $idNotificacion = $request->input('idNotificacion', '');

            if ($idNotificacion) {
                $notification = SystemNotification::findOrFail($idNotificacion);
                $notification->active = 0;
                $notification->save();
    
                // Controller::printJSON($notification);
                
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        } catch (\Exception $e) {
            Log::error('Error en returnArraySolicitudesCanjesTabla: ' . $e->getMessage());
            return response()->json(['success' => false]);
        }
    }
}
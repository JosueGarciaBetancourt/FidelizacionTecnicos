<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use Illuminate\Http\Request;

class SystemNotificationController extends Controller
{
    public function getActiveNotifications()
    {
        $notifications = SystemNotification::where('active', true)
            ->orderBy('created_at', 'desc')
            //->take(5)
            ->get();
            
        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
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
    public function deactivateNotification($id)
    {
        $notification = SystemNotification::findOrFail($id);
        $notification->active = false;
        $notification->save();
        
        return response()->json(['success' => true]);
    }
}
<?php

namespace App\Http\Controllers;

use GuzzleHttp\RetryMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SystemNotificationController;


class DashboardController extends Controller
{   
    public function configuracion()
    {
        // Obtener las notificaciones
        $notifications = SystemNotificationController::getActiveNotifications();

        return view('dashboard.configuracion', compact('notifications'));
    }
}

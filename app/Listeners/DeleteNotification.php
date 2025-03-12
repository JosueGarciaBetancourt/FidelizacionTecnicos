<?php

namespace App\Listeners;

use App\Events\NotificationReviewed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Log;

class DeleteNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(NotificationReviewed $event)
    {   
        // Buscar y eliminar la notificación
        $notification = SystemNotification::find($event->idNotificacion);

        if ($notification) {
            $notification->delete();
            Log::info("Notificación ID {$event->idNotificacion} eliminada físicamente de la BD.");
        }
    }
}

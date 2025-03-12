<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class NotificationReviewed
{
    use Dispatchable, SerializesModels;

    public $idNotificacion;

    public function __construct($idNotificacion)
    {
        $this->idNotificacion = $idNotificacion;
    }
}

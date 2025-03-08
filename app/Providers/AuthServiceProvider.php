<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Canje;
use App\Policies\CanjePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * El mapa de políticas del modelo para la aplicación.
     *
     * @var array
     */
    protected $policies = [
        Canje::class => CanjePolicy::class, // Registrar la política
    ];

    /**
     * Registra cualquier servicio de autenticación y autorización.
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}

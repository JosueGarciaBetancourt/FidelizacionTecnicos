<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Verificar si estamos en entorno de build (no acceder a la DB)
        if ($this->app->environment('build')) {
            return;
        }

        // Si no existe la tabla, no continuar
        if (!Schema::hasTable('settings')) {
            return;
        }

        // Cargar configuración desde la DB (solo en runtime)
        $settings = Cache::rememberForever('settings_cache', function () {
            return Setting::pluck('value', 'key')->map(function ($value, $key) {
                return in_array($key, ['maxdaysCanje', 'puntosMinRangoPlata', 'puntosMinRangoOro', 'puntosMinRangoBlack']) 
                    ? (int) $value 
                    : $value;
            })->toArray();
        });

        config([
            'settings.adminUsername' => $settings['adminUsername'] ?? 'admin',
            'settings.emailDomain' => $settings['emailDomain'] ?? 'dimacof.com',
            'settings.adminEmail' => ($settings['adminUsername'] ?? 'admin') . '@' . ($settings['emailDomain'] ?? 'dimacof.com'),
            'settings.maxdaysCanje' => $settings['maxdaysCanje'] ?? 90,
            'settings.puntosMinRangoPlata' => $settings['puntosMinRangoPlata'] ?? 0,
            'settings.puntosMinRangoOro' => $settings['puntosMinRangoOro'] ?? 24000,
            'settings.puntosMinRangoBlack' => $settings['puntosMinRangoBlack'] ?? 60000,
        ]);
    }
}
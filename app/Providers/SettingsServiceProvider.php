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
        // Verifica si la tabla "settings" existe antes de hacer consultas
        if (!Schema::hasTable('settings') || !Schema::hasTable('cache')) {
            return;
        }

        // Obtener la configuración desde caché o regenerarla
        $settings = Cache::rememberForever('settings_cache', function () {
            return Setting::pluck('value', 'key')->map(function ($value, $key) {
                // Convertir valores específicos a enteros
                return in_array($key, ['maxdaysCanje', 'puntosMinRangoPlata', 'puntosMinRangoOro',
                                        'puntosMinRangoBlack', 'unidadesRestantesRecompensasNotificacion',
                                        'diasAgotarVentaIntermediadaNotificacion']) ? (int) $value : $value;
            })->toArray();
        });

        // Usar los valores directamente desde el caché
        $adminUsername = $settings['adminUsername'] ?? 'admin';
        $emailDomain = $settings['emailDomain'] ?? 'dimacof.com';
        $maxdaysCanje = $settings['maxdaysCanje'] ?? 90;
        $puntosMinRangoPlata = $settings['puntosMinRangoPlata'] ?? 0;
        $puntosMinRangoOro = $settings['puntosMinRangoOro'] ?? 24000;
        $puntosMinRangoBlack = $settings['puntosMinRangoBlack'] ?? 60000;
        $unidadesRestantesRecompensasNotificacion = $settings['unidadesRestantesRecompensasNotificacion'] ?? 15;
        $diasAgotarVentaIntermediadaNotificacion = $settings['diasAgotarVentaIntermediadaNotificacion'] ?? 7;
        $adminEmail = $adminUsername . '@' . $emailDomain;

        // Agregar valores a la configuración de Laravel
        config([
            'settings.adminUsername' => $adminUsername,
            'settings.emailDomain' => $emailDomain,
            'settings.adminEmail' => $adminEmail,
            'settings.maxdaysCanje' => $maxdaysCanje,
            'settings.puntosMinRangoPlata' => $puntosMinRangoPlata,
            'settings.puntosMinRangoOro' => $puntosMinRangoOro,
            'settings.puntosMinRangoBlack' => $puntosMinRangoBlack,
            'settings.unidadesRestantesRecompensasNotificacion' => $unidadesRestantesRecompensasNotificacion,
            'settings.diasAgotarVentaIntermediadaNotificacion' => $diasAgotarVentaIntermediadaNotificacion,
        ]);
    }
}

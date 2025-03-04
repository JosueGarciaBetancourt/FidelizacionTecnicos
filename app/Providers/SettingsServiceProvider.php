<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Verifica si la base de datos está disponible antes de hacer consultas
        if (Schema::hasTable('settings')) {
            $adminUsername = Setting::where('key', 'adminUsername')->value('value') ?? 'admin';
            $emailDomain = Setting::where('key', 'emailDomain')->value('value') ?? 'dimacof.com';
            $adminEmail = $adminUsername . '@' . $emailDomain;
            $maxdaysCanje = (int) Setting::where('key', 'maxdaysCanje')->value('value');

            // Agregar valores a la configuración de Laravel
            config([
                'settings.adminUsername' => $adminUsername,
                'settings.emailDomain' => $emailDomain,
                'settings.adminEmail' => $adminEmail,
                'settings.maxdaysCanje' => $maxdaysCanje,
            ]);
        }
    }
}

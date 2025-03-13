<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Models\Tecnico;
use App\Models\Recompensa;
use Illuminate\Http\Request;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\TecnicoController;
use App\Models\TecnicoNotification;
use App\Models\VentaIntermediada;

class ConfiguracionController extends Controller
{
    public function changeSettingsVariables(Request $request)
    {
        $validatedData = $request->validate([
            'keys' => 'required|array',
            'values' => 'required|array',
            'keys.*' => 'required|string',
            'values.*' => 'required|string',
            'originConfig' => 'nullable|string',
        ]);

        // Iterar sobre los valores enviados
        foreach ($validatedData['keys'] as $index => $key) {
            $value = $validatedData['values'][$index];

            // Actualizar configuración correctamente
            Setting::updateOrInsert(['key' => $key], ['value' => $value]);

            // Validar si es un cambio de rango y actualizar técnicos
            // Para ahorrar recursos siempre y cuando se reciba puntosMinRangoBlack 
            if (in_array($key, [/* 'puntosMinRangoPlata', 'puntosMinRangoOro', */'puntosMinRangoBlack'])) { 
                $this->updateRangoTecnicos();
            } else if ($key === "emailDomain") {
                $this->updateUserEmails($value);
            } else if ($key === "unidadesRestantesRecompensasNotificacion") {
                $this->createRecompensasNotifications($value);
            } else if ($key === "diasAgotarVentaIntermediadaNotificacion") {
                $this->createAgotamientoVentasIntermediadasNotifications($value);
            }
        }

        // Limpiar caché de configuración para reflejar cambios
        Cache::forget('settings_cache');

        // Retornar según el origen de la request
        return match ($validatedData['originConfig'] ?? '') {
            "originProfileOwn" => redirect()->route('usuarios.create')->with('successDominioCorreoUpdate', 'Dominio de correo actualizado correctamente.'),
            default => redirect()->route('configuracion.create')->with('success', 'Configuraciones actualizadas correctamente.'),
        };
    }

    private function updateUserEmails(string $newDomain)
    {
        User::all()->each(function ($user) use ($newDomain) {
            $username = strstr($user->email, '@', true); // Obtener parte antes del @
            $user->update(['email' => $username . '@' . $newDomain]);
        });
    }

    private function updateRangoTecnicos()
    {
        $tecnicos = Tecnico::all();

        $tecnicos->each(function ($tecnico) {
            $oldRango = $tecnico->rangoTecnico;
            $newRango = TecnicoController::getRango($tecnico->historicoPuntos_Tecnico);
            $tecnico->rangoTecnico = $newRango;

            // Crear notificación de sistema y notificación de técnico (móvil) al detectar cambio de rango del técnico
            if ($newRango !== $oldRango) {
                SystemNotification::create([
                    'icon' => 'workspace_premium',
                    'tblToFilter' => 'tblTecnicos',
                    'title' => 'Cambio de rango de técnico',
                    'item' => $tecnico['idTecnico'] . ' | ' . $tecnico['nombreTecnico'],
                    'description' => 'subió a rango ' . $newRango,
                    'routeToReview' => 'tecnicos.create',
                ]);
                TecnicoNotification::create([
                    'idTecnico' => $tecnico['idTecnico'],
                    'description' => '¡Felicidades! Subiste de rango. Ahora eres rango ' . $newRango,
                ]);
            }
        });

        $tecnicosArray = $tecnicos->map(function ($tecnico) {
            return collect($tecnico)
                ->except(['idNameOficioTecnico', 'idNombreTecnico', 'idsOficioTecnico'])
                ->merge([
                    'created_at' => Carbon::parse($tecnico->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($tecnico->updated_at)->format('Y-m-d H:i:s')
                ])
                ->toArray();
        });
        
        Tecnico::upsert($tecnicosArray->toArray(), ['idTecnico'], ['rangoTecnico', 'updated_at']);
    }

    
    private function createRecompensasNotifications(int $remainingUnits)
    {
        try {
            $recompensas = Recompensa::all();

            $recompensas->each(function ($recompensa) use ($remainingUnits) {
                if ($recompensa['stock_Recompensa'] <= $remainingUnits) {
                    SystemNotification::create([
                        'icon' => 'timer',
                        'tblToFilter' => 'tblRecompensas',
                        'title' => 'Recompensa a punto de agotar stock',
                        'item' => $recompensa['idRecompensa'] . ' | ' . $recompensa['descripcionRecompensa'],
                        'description' => 'Tiene ' . $remainingUnits . ' o menos unidades restantes',
                        'routeToReview' => 'recompensas.create',
                    ]);
                }
            });
        } catch (\Exception $e) {
            dd("createRecompensasNotifications: " . $e);
        }
    }

    private function createAgotamientoVentasIntermediadasNotifications(int $remainingDays)
    {
        try {
            $ventas = VentaIntermediada::all();
            
            $ventas->each(function ($venta) use ($remainingDays){
                if (config('settings.maxdaysCanje') >= $venta['diasTranscurridos'] && 
                    config('settings.maxdaysCanje') - $venta['diasTranscurridos'] == $remainingDays) {
                    TecnicoNotification::create([
                        'idTecnico' => $venta['idTecnico'],
                        'idVentaIntermediada' => $venta['idVentaIntermediada'],
                        'description' => 'Venta intermediada con ' . $remainingDays . ' días para agotarse',
                    ]);
                }
            });
        } catch (\Exception $e) {
            dd("createAgotamientoVentasIntermediadasNotifications: " . $e);
        }
    }
}

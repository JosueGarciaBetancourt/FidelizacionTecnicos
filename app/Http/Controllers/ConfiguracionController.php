<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Models\Tecnico;
use App\Models\Recompensa;
use Illuminate\Http\Request;
use App\Models\VentaIntermediada;
use App\Models\SystemNotification;
use App\Models\TecnicoNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\VentaIntermediadaController;

class ConfiguracionController extends Controller
{
    private $emailDomainKey = 'emailDomain';
    private $adminUsernameKey = 'adminUsername';
    private $maxdaysCanjeKey = 'maxdaysCanje';
    private $puntosMinRangoPlataKey = 'puntosMinRangoPlata';
    private $puntosMinRangoOroKey = 'puntosMinRangoOro';
    private $puntosMinRangoBlackKey = 'puntosMinRangoBlack';
    private $unidadesRestantesRecompensasNotificacionKey = 'unidadesRestantesRecompensasNotificacion';
    private $diasAgotarVentaIntermediadaNotificacionKey = 'diasAgotarVentaIntermediadaNotificacion';

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

            if ($key === $this->emailDomainKey) {
                $this->updateEstadoVentasIntermediadas($value);
            } else if (in_array($key, [/* $this->puntosMinRangoPlataKey, $this->puntosMinRangoOroKey,  */$this->puntosMinRangoBlackKey])) { 
                // Para ahorrar recursos se comentan algunos rangos siempre y cuando se reciba puntosMinRangoBlack 
                $this->updateRangoTecnicos();
            } else if ($key === $this->emailDomainKey) {
                $this->updateUserEmails($value);
            } else if ($key === $this->unidadesRestantesRecompensasNotificacionKey) {
                $this->createRecompensasNotifications($value);
            } else if ($key === $this->diasAgotarVentaIntermediadaNotificacionKey) {
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

        // Verificar si hay técnicos antes de continuar
        if ($tecnicos->isEmpty()) {
            return;
        }

        $idTecnicosArray = $tecnicos->pluck('idTecnico')->toArray();
        
        // Eliminar todas las notificaciones asociadas en una sola consulta
        SystemNotification::where(function ($query) use ($idTecnicosArray) {
            foreach ($idTecnicosArray as $id) {
                $query->orWhere('item', 'LIKE', "%{$id}%");
            }
        })->delete();

        TecnicoNotification::whereIn('idTecnico', $tecnicos->pluck('idTecnico'))->delete();
        
        $tecnicos->each(function ($tecnico) {
            $oldRango = $tecnico->rangoTecnico;
            $newRango = TecnicoController::getRango($tecnico->historicoPuntos_Tecnico);
            $tecnico->rangoTecnico = $newRango;

            // Si el rango cambió, generar notificaciones
            if ($newRango !== $oldRango) {
                // Crear nueva notificación de sistema
                SystemNotification::create([
                    'icon' => 'workspace_premium',
                    'tblToFilter' => 'tblTecnicos',
                    'title' => 'Cambio de rango de técnico',
                    'item' => "{$tecnico->idTecnico} | {$tecnico->nombreTecnico}",
                    'description' => 'subió a rango ' . $newRango,
                    'routeToReview' => 'tecnicos.create',
                ]);

                TecnicoNotification::create([
                    'idTecnico' => $tecnico->idTecnico,
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

    private function updateEstadoVentasIntermediadas($maxdaysCanje) 
    {
        try {
            VentaIntermediada::all()->each(function ($venta) use ($maxdaysCanje) {
                $idEstadoVenta = VentaIntermediadaController::returnStateIdVentaIntermediada(
                    $venta->idVentaIntermediada,
                    $venta->puntosActuales_VentaIntermediada,
                    $maxdaysCanje
                );

                $venta->update(['idEstadoVenta' => $idEstadoVenta]);
            });
        } catch (\Exception $e) {
            dd("updateEstadoVentasIntermediadas: " . $e);
            //Log::error("Error en createAgotamientoVentasIntermediadasNotifications: " . $e->getMessage());
        }
    }

    private function createRecompensasNotifications(int $remainingUnits)
    {
        try {
            $recompensas = Recompensa::all();

            $idRecompensasArray = $recompensas->pluck('idRecompensa')->toArray();
        
            // Eliminar todas las notificaciones asociadas en una sola consulta
            SystemNotification::where(function ($query) use ($idRecompensasArray) {
                foreach ($idRecompensasArray as $id) {
                    $query->orWhere('item', 'LIKE', "%{$id}%");
                }
            })->delete();

            $recompensas->each(function ($recompensa) use ($remainingUnits) {
              
                if ($recompensa->stock_Recompensa <= $remainingUnits) {
                    SystemNotification::create([
                        'icon' => 'timer',
                        'tblToFilter' => 'tblRecompensas',
                        'title' => 'Recompensa a punto de agotar stock',
                        'item' => $recompensa->idRecompensa . ' | ' . $recompensa->descripcionRecompensa,
                        'description' => "tiene {$recompensa->stock_Recompensa} unidades restantes",
                        'routeToReview' => 'recompensas.create',
                    ]);
                }
            });
        } catch (\Exception $e) {
            dd("createRecompensasNotifications: " . $e);
        }
    }

    private function createAgotamientoVentasIntermediadasNotifications(int $diasAgotarVentaIntermediadaNotificacion)
    {
        try {
            $maxDaysCanje = Setting::where('key', 'maxDaysCanje')->value('value');
            $maxDaysNotification = $maxDaysCanje - $diasAgotarVentaIntermediadaNotificacion;
            
            $ventas = VentaIntermediada::whereIn('idEstadoVenta', [1, 2, 4, 5])->get()
                ->filter(function ($venta) use ($maxDaysNotification, $maxDaysCanje) {
                    return $venta->diasTranscurridos <= $maxDaysCanje && $venta->diasTranscurridos >= $maxDaysNotification; 
            });

            // Eliminar todas las notificaciones de las ventas en una sola consulta
            TecnicoNotification::whereIn('idVentaIntermediada', $ventas->pluck('idVentaIntermediada'))->delete();

            $notificaciones = $ventas->map(function ($venta) use ($maxDaysCanje) {
                $remainingDays = $maxDaysCanje - $venta->diasTranscurridos;
                return [
                    'idTecnico' => $venta->idTecnico,
                    'idVentaIntermediada' => $venta->idVentaIntermediada,
                    'description' => 'La venta intermediada ' .  $venta->idVentaIntermediada . ' se agotará en ' . $remainingDays . ' días',
                ];
            })->filter();

            TecnicoNotification::insert($notificaciones->toArray());
        } catch (\Exception $e) {
            dd("createAgotamientoVentasIntermediadasNotifications: " . $e);
            Log::error("Error en createAgotamientoVentasIntermediadasNotifications: " . $e->getMessage());
        }
    }
}

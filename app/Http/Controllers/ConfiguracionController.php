<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Rango;
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
use App\Http\Controllers\RangoController;

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
                $this->updateUserEmails($value);
            } else if ($key === $this->unidadesRestantesRecompensasNotificacionKey) {
                $this->createRecompensasNotifications($value);
            } else if ($key === $this->diasAgotarVentaIntermediadaNotificacionKey) {
                $this->createAgotamientoVentasIntermediadasNotifications($value);
            }
        }

        // Limpiar caché de configuración para reflejar cambios
        Cache::forget('settings_cache');

        return match ($validatedData['originConfig'] ?? '') {
            "originProfileOwn" => 
                Controller::$newNotifications
                    ? redirect()->route('usuarios.create')
                        ->with('successDominioCorreoUpdate', 'Dominio de correo actualizado correctamente.')
                        ->with('newNotifications', '-')
                    : redirect()->route('usuarios.create')
                        ->with('successDominioCorreoUpdate', 'Dominio de correo actualizado correctamente.'),
            default => redirect()->route('configuracion.create')->with('successConfig', 'Configuraciones actualizadas correctamente.'),
        };
    }

    private function updateUserEmails(string $newDomain)
    {
        User::all()->each(function ($user) use ($newDomain) {
            $username = strstr($user->email, '@', true); // Obtener parte antes del @
            $user->update(['email' => $username . '@' . $newDomain]);
        });
    }

    public static function updateRangoTecnicos()
    {
        try {
            // Obtener solo las columnas necesarias
            $tecnicos = Tecnico::select('idTecnico', 'nombreTecnico', 'historicoPuntos_Tecnico', 'idRango')->get();

            if ($tecnicos->isEmpty()) {
                return;
            }

            // Filtrar solo los técnicos cuyo rango ha cambiado
            $tecnicosActualizados = $tecnicos->filter(function ($tecnico) {
                return TecnicoController::getIDRango($tecnico->historicoPuntos_Tecnico) !== $tecnico->idRango;
            });

            if ($tecnicosActualizados->isEmpty()) {
                return;
            }

            // Obtener los nombres de rangos de una sola consulta
            $rangos = Rango::pluck('nombre_Rango', 'idRango');

            // Eliminar todas las notificaciones antes de crear nuevas
            SystemNotification::truncate();
            TecnicoNotification::truncate();

            $tecnicosActualizados->each(function ($tecnico) use ($rangos) {
                $newIDRango = TecnicoController::getIDRango($tecnico->historicoPuntos_Tecnico);
                $newRango = $rangos[$newIDRango] ?? 'Desconocido';

                $descriptionSystemNoti = ($newIDRango == 1)
                    ? "ahora está $newRango"
                    : "subió a rango " . $newRango;

                $descriptionTecNoti = ($newIDRango == 1)
                    ? "Aún no tienes un rango, sigue intermediando ventas."
                    : "¡Felicidades! Subiste de rango. Ahora eres rango " . $newRango;

                // Crear notificación de sistema
                SystemNotification::create([
                    'icon' => 'workspace_premium',
                    'tblToFilter' => 'tblTecnicos',
                    'title' => 'Cambio de rango de técnico',
                    'item' => "{$tecnico->idTecnico} | {$tecnico->nombreTecnico}",
                    'description' => $descriptionSystemNoti,
                    'routeToReview' => 'tecnicos.create',
                ]);

                // Crear notificación personal para el técnico
                TecnicoNotification::create([
                    'idTecnico' => $tecnico->idTecnico,
                    'description' => $descriptionTecNoti,
                ]);

                Controller::$newNotifications = true;

                // Actualizar el rango en la base de datos
                $tecnico->update([
                    'idRango' => $newIDRango,
                    'updated_at' => now(),
                ]);
            });
        } catch (\Exception $e) {
            dd($e);
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
                    Controller::$newNotifications = true;
                }
            });
        } catch (\Exception $e) {
            dd("createRecompensasNotifications: " . $e);
        }
    }

    public static function createAgotamientoVentasIntermediadasNotifications(int $diasAgotarVentaIntermediadaNotificacion)
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
                
                $description = match ($remainingDays) {
                    0 => 'La venta intermediada ' .  $venta->idVentaIntermediada . ' se agotó hoy',
                    1 => 'La venta intermediada ' .  $venta->idVentaIntermediada . ' se agotará en 1 día',
                    default => 'La venta intermediada ' .  $venta->idVentaIntermediada . ' se agotará en ' . $remainingDays . ' días',
                };
                
                return [
                    'idTecnico' => $venta->idTecnico,
                    'idVentaIntermediada' => $venta->idVentaIntermediada,
                    'description' => $description,
                ];
            })->filter();

            TecnicoNotification::insert($notificaciones->toArray());
        } catch (\Exception $e) {
            dd("createAgotamientoVentasIntermediadasNotifications: " . $e);
            Log::error("Error en createAgotamientoVentasIntermediadasNotifications: " . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Models\Tecnico;
use App\Http\Controllers\TecnicoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

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
            if (in_array($key, [/* 'puntosMinRangoPlata', 'puntosMinRangoOro',  */'puntosMinRangoBlack'])) {
                $this->updateRangoTecnicos();
            } elseif ($key === "emailDomain") {
                $this->updateUserEmails($value);
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
            $tecnico->rangoTecnico = TecnicoController::getRango($tecnico->historicoPuntos_Tecnico);
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

}

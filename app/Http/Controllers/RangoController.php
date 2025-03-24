<?php

namespace App\Http\Controllers;

use App\Models\Rango;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\ConfiguracionController;

class RangoController extends Controller
{
    public function returnNuevoCodigoRango() {
        $lastNumberRangoID = Rango::max('idRango');
        if (!$lastNumberRangoID) {
            return 'RAN-01';
        }
        $newNumberRangoID = $lastNumberRangoID + 1;
        $newCodigoRango = 'RAN-'. str_pad($newNumberRangoID, 2, '0', STR_PAD_LEFT);
        return $newCodigoRango;
    }

    public function create()
    {
        $rangos = Rango::all();
        $nuevoCodigoRango = $this->returnNuevoCodigoRango();

        // Obtener las notificaciones
        $notifications = SystemNotificationController::getActiveNotifications();
        return view('dashboard.rangos', compact('rangos', 'nuevoCodigoRango', 'notifications'));
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'nombre_Rango' => 'required|string',
                'descripcion_Oficio' => 'nullable|string',
                'puntosMinimos_Rango' => 'required|numeric',
            ]);

            $rango = new Rango($validatedData);
            $rango->save();
            $messageStore = 'Rango guardado correctamente';
            return redirect()->route('rangos.create')->with('successRangoStore', $messageStore);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('rangos.create')->withErrors('Ocurrió un error al intentar crear el rango. Por favor, inténtelo de nuevo.');
        }
    }

    public function update(Request $request) {
        $validatedData = $request->validate([
            'idRango' => 'required|exists:Rangos,idRango',
            'descripcion_Rango' => 'nullable|string',
            'puntosMinimos_Rango' => 'required|string',
        ]);

        $rangoSolicitado = Rango::find($validatedData['idRango']);
        
        $rangoSolicitado->update([
            'descripcion_Rango' => $validatedData['descripcion_Rango'],
            'puntosMinimos_Rango' => $validatedData['puntosMinimos_Rango'],
        ]);
        
        // Actualizar técnicos y crear notificaciones si es necesario
        ConfiguracionController::updateRangoTecnicos();

        // Actualizar en la tabla Settings
        $this->updateRangoInSettingsTable($rangoSolicitado);

        $messageUpdate = 'Rango actualizado correctamente';
        return redirect()->route('rangos.create')->with('successRangoUpdate', $messageUpdate);
    }

    public function updateRangoInSettingsTable($rango)
    {
         // Verificar que los valores existen antes de actualizar
        if (!isset($rango->nombre_Rango) || !isset($rango->puntosMinimos_Rango)) {
            return;
        }

        // Actualizar la configuración en la tabla settings
        /* $updatedSetting = Setting::where('key', 'puntosMinRango' . $rango->nombre_Rango)->update([
            'value' => $rango->puntosMinimos_Rango,           
        ]); */
        $updatedSetting = Setting::where('key', 'puntosMinRango' . $rango->nombre_Rango)->first();

        $updatedSetting->update([
            'value' => $rango->puntosMinimos_Rango,
        ]);

        //dd("updatedSetting: ", $updatedSetting);

        // Limpiar caché de configuración para reflejar cambios
        Cache::forget('settings_cache');
    }
}

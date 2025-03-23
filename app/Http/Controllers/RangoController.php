<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rango;
use App\Models\Setting;

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
        
        $messageUpdate = 'Rango actualizado correctamente';
        return redirect()->route('rangos.create')->with('successRangoUpdate', $messageUpdate);
    }

    public static function updateRangosFromSettings()
    {
        // Obtener los valores de la tabla Settings
        $plata = Setting::where('key', 'puntosMinRangoPlata')->value('value');
        $oro = Setting::where('key', 'puntosMinRangoOro')->value('value');
        $black =Setting::where('key', 'puntosMinRangoBlack')->value('value');

        // Actualizar cada rango
        Rango::where('nombre_Rango', 'Plata')->update(['puntosMinimos_Rango' => $plata]);
        Rango::where('nombre_Rango', 'Oro')->update(['puntosMinimos_Rango' => $oro]);
        Rango::where('nombre_Rango', 'Black')->update(['puntosMinimos_Rango' => $black]);

        return redirect()->back()->with('success', 'Rangos actualizados correctamente.');
    }
}

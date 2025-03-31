<?php

namespace App\Http\Controllers;

use App\Models\Rango;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $nuevoIdRango = Rango::max('idRango') ? Rango::max('idRango') + 1 : 1;
        $nuevoCodigoRango = $this->returnNuevoCodigoRango();

        //dd($nuevoCodigoRango);

        $rangosEliminados = Rango::onlyTrashed()->get();

        // Obtener las notificaciones
        $notifications = SystemNotificationController::getActiveNotifications();

        return view('dashboard.rangos', compact('rangos', 'nuevoIdRango', 'nuevoCodigoRango', 'notifications', 'rangosEliminados'));
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'idRango' => 'required|numeric',
                'nombre_Rango' => 'required|string',
                'descripcion_Rango' => 'nullable|string',
                'puntosMinimos_Rango' => 'required|numeric',
                'colorTexto_Rango' => 'required|string|max:7', // Formato hexadecimal
                'colorFondo_Rango' => 'required|string|max:7',
            ]);

            
            $rango = new Rango($validatedData);
            $rango->save();
        
            Controller::$newNotifications = false;

            // Actualizar técnicos y crear notificaciones si es necesario
            ConfiguracionController::updateRangoTecnicos();
            
            $messageStore = 'Rango guardado correctamente';

            return Controller::$newNotifications
                ? redirect()->route('rangos.create')->with('successRangoStore', $messageStore)
                                                    ->with('newNotifications', '-')
                : redirect()->route('rangos.create')->with('successRangoStore', $messageStore);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('rangos.create')->withErrors('Ocurrió un error al intentar crear el rango. Por favor, inténtelo de nuevo.');
        }
    }

    public function update(Request $request) {
        try {
            $validatedData = $request->validate([
                'idRango' => 'required|exists:Rangos,idRango',
                'descripcion_Rango' => 'nullable|string',
                'puntosMinimos_Rango' => 'required|string',
                'colorTexto_Rango' => 'required|string|max:7', // Formato hexadecimal
                'colorFondo_Rango' => 'required|string|max:7',
            ]);
    
            $rangoSolicitado = Rango::find($validatedData['idRango']);
    
            $rangoSolicitado->update([
                'descripcion_Rango' => $validatedData['descripcion_Rango'],
                'puntosMinimos_Rango' => $validatedData['puntosMinimos_Rango'],
                'colorTexto_Rango' => $validatedData['colorTexto_Rango'],
                'colorFondo_Rango' => $validatedData['colorFondo_Rango'],
            ]);
    
            Controller::$newNotifications = false;

            // Actualizar técnicos y crear notificaciones si es necesario
            ConfiguracionController::updateRangoTecnicos();
    
            return Controller::$newNotifications
                ? redirect()->route('rangos.create')->with('successRangoUpdate', 'Rango actualizado correctamente')
                                                        ->with('newNotifications', '-')
                : redirect()->route('rangos.create')->with('successRangoUpdate', 'Rango actualizado correctamente');
        } catch (\Exception $e) {
                dd($e);
            return redirect()->route('rangos.create')->withErrors('Ocurrió un error al intentar crear el rango. Por favor, inténtelo de nuevo.');
        }
    }

    public function disable(Request $request) {
        $validatedData = $request->validate([
            'idRango' => 'required|exists:Rangos,idRango',
        ]);

        $rango = Rango::where('idRango', $validatedData['idRango'])->first();
    
        if ($rango) {
            // Aplica soft delete
            $rango->delete();
            $messageDisable = 'Rango inhabilitado correctamente';
        } else {
            $messageDisable = 'Rango no encontrado';
        }
    
        Controller::$newNotifications = false;

        // Actualizar técnicos y crear notificaciones si es necesario
        ConfiguracionController::updateRangoTecnicos();

        return Controller::$newNotifications
            ? redirect()->route('rangos.create')->with('successRangoDisable', $messageDisable)
                                                    ->with('newNotifications', '-')
            : redirect()->route('rangos.create')->with('successRangoDisable', $messageDisable);
    }

    public function restore(Request $request) {
        try {
            $validatedData = $request->validate([
                'idRango' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();
            
            //dd($validatedData);

            $oficioEliminado = Rango::onlyTrashed()->where('idRango', $validatedData['idRango'])->first();
            
            if (!$oficioEliminado) {
                return redirect()->route('rangos.create')->withErrors('Oficio no encontrado o ya restaurado.');
            }
            
            $oficioEliminado->restore();
            
            DB::commit();

            Controller::$newNotifications = false;

            // Actualizar técnicos y crear notificaciones si es necesario
            ConfiguracionController::updateRangoTecnicos();

            return Controller::$newNotifications
                ? redirect()->route('rangos.create')->with('successRangoRestaurado', 'Rango restaurado correctamente.')
                                                    ->with('newNotifications', '-')
                : redirect()->route('rangos.create')->with('successRangoRestaurado', 'Rango restaurado correctamente.');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('rangos.create')->withErrors('Ocurrió un error al intentar restaurar el rango. Por favor, inténtelo de nuevo.');
        }
    }

    public function delete(Request $request) {
        try {
            $validatedData = $request->validate([
                'idRango' => 'required|exists:Rangos,idRango',
            ]);
    
            // Encuentra el oficio
            $rango = Rango::findOrFail($validatedData['idRango']);
    
            // Verifica si tiene técnicos asociados en la tabla intermedia
            if ($rango->tecnicos()->exists()) {
                return redirect()->route('rangos.create')->with('errorRangoDelete', 'El rango no puede ser eliminado porque hay técnicos asociados.');
            }
    
            // Si no hay técnicos asociados, eliminar el oficio
            $rango->forceDelete();

            return Controller::$newNotifications
                ? redirect()->route('rangos.create')->with('successRangoDelete', 'Rango eliminado correctamente')
                                                    ->with('newNotifications', '-')
                : redirect()->route('rangos.create')->with('successRangoDelete', 'Rango eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('rangos.create');
        }
    }
}

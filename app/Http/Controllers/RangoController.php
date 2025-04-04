<?php

namespace App\Http\Controllers;

use App\Models\Rango;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\ConfiguracionController;
use Barryvdh\DomPDF\Facade\Pdf;

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
    
        // Verifica si tiene técnicos asociados en la tabla intermedia
        if ($rango->tecnicos()->exists()) {
            return redirect()->route('rangos.create')->with('errorRangoDisable', 'El rango no puede ser inhabilitado porque hay técnicos asociados a este');
        }
    
        $rango->delete();

        Controller::$newNotifications = false;

        // Actualizar técnicos y crear notificaciones si es necesario
        ConfiguracionController::updateRangoTecnicos();

        return Controller::$newNotifications
            ? redirect()->route('rangos.create')->with('successRangoDisable', 'Rango inhabilitado correctamente')->with('newNotifications', '-')
            : redirect()->route('rangos.create')->with('successRangoDisable', 'Rango inhabilitado correctamente');
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

    public function returnArrayRangos() {
        $rangos = Rango::all();
        $index = 1;

        $data = $rangos->map(function ($rango) use (&$index) {
            return [
                'index' => $index++,
                'codigoRango' => $rango->codigoRango,
                'nombre_Rango' => $rango->nombre_Rango,
                'descripcion_Rango' => $rango->descripcion_Rango,
                'puntosMinimos_Rango' => $rango->puntosMinimos_Rango,
                'created_at' => $rango->created_at,
                'updated_at' => $rango->updated_at,
            ];
        });
        
        return $data->toArray();
    }

    public function exportarAllRangosPDF()
    {
        try {
            // Cargar datos de técnicos con rangos
            $data = $this->returnArrayRangos();

            // Verificar si hay datos para exportar
            if (count($data) === 0) {
                throw new \Exception("No hay datos disponibles para exportar la tabla de rangos.");
            }

            // Configurar los parámetros del PDF
            $paperSize = 'A4';
            $view = 'tables.tablaRangosPDFA4';
            $fileName = "Club de técnicos DIMACOF-Listado de Rangos-" . $this->obtenerFechaHoraFormateadaExportaciones() . ".pdf";

            // Generar el PDF con los datos
            $pdf = Pdf::loadView($view, ['data' => $data])
                    ->setPaper($paperSize, 'landscape');

            // Retornar el PDF para visualizar o descargar
            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error("Error en exportarAllRangosPDF: " . $e->getMessage());

            // Retornar una respuesta clara al usuario
            return response()->json([
                'message' => 'Ocurrió un error al generar el PDF.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

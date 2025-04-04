<?php

namespace App\Http\Controllers;

use App\Models\Recompensa;
use Illuminate\Http\Request;
use App\Models\TipoRecompensa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SystemNotificationController;

class TipoRecompensaController extends Controller
{
    public static function returnNuevoCodigoTipoRecompensa() {
        $ultimoNumberTipoRecompensaID = TipoRecompensa::max('idTipoRecompensa');
        if (!$ultimoNumberTipoRecompensaID) {
            return 'TIPO-01';
        }
        $nuevoNumberTipoRecompensaID = $ultimoNumberTipoRecompensaID + 1;
        $nuevoCodigoTipoRecompensa= 'TIPO-'. str_pad($nuevoNumberTipoRecompensaID, 2, '0', STR_PAD_LEFT);
        return $nuevoCodigoTipoRecompensa;
    }

    public function create() {
        $nuevoIdTipoRecompensa = TipoRecompensa::max('idTipoRecompensa') ? TipoRecompensa::max('idTipoRecompensa') + 1 : 1;
        $nuevoCodigoTipoRecompensa = self::returnNuevoCodigoTipoRecompensa();

        $tiposRecompensas = TipoRecompensa::all()->reject(function ($recompensa) {
            return $recompensa->idTipoRecompensa == 1;
        })->values(); // Reindexa los índices de la colección

        $recompensas = Recompensa::query()
                        ->join('TiposRecompensas', 'Recompensas.idTipoRecompensa', '=', 'TiposRecompensas.idTipoRecompensa')
                        ->select(['Recompensas.*', 'TiposRecompensas.nombre_TipoRecompensa'])
                        ->whereNull('Recompensas.deleted_at')
                        ->orderBy('Recompensas.idRecompensa', 'ASC') 
                        ->get(); 
                        
        // dd($tiposRecompensas);

        // Obtener todas las recompensas no activas (soft deleted) con sus tipos
        $tiposRecompensasEliminados = TipoRecompensa::onlyTrashed()->get();

        // Obtener las notificaciones
        $notifications = SystemNotificationController::getActiveNotifications();
        
        return view('dashboard.tiposRecompensas', compact('nuevoIdTipoRecompensa', 'nuevoCodigoTipoRecompensa', 'tiposRecompensas', 
                                                        'recompensas', 'tiposRecompensasEliminados', 'notifications'));
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'idTipoRecompensa' => 'required|numeric',
                'nombre_TipoRecompensa' => 'required|string',
                'descripcion_TipoRecompensa' => 'nullable|string',
                'colorTexto_TipoRecompensa' => 'required|string|max:7', // Formato hexadecimal
                'colorFondo_TipoRecompensa' => 'required|string|max:7',
            ]);
    
            DB::beginTransaction();
    
            // Crear una instancia pero no guardar
            $tipoRecompensa = new TipoRecompensa($validatedData);
            // dd($tipoRecompensa); // Aquí puedes inspeccionar el modelo sin que afecte el ID
            $tipoRecompensa->save(); // Guarda solo cuando estés seguro
            DB::commit();
            $messageStore = 'Tipo de recompensa guardado correctamente';

            // Obtener el origen de la solicitud
            $origin = $request->input('origin'); // Con JS se modifica el valor del input en modalRegistrarNuevoTipoRecompensa.blade.php

            // Redirigir basado en el origen
            switch ($origin) { 
                case 'recompensas.create':
                    return redirect()->route('recompensas.create')->with('successTipoRecompensaStore', $messageStore);
                default:
                    return redirect()->route('tiposRecompensas.create')->with('successTipoRecompensaStore', $messageStore);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tiposRecompensas.create')->withErrors('Ocurrió un error al intentar crear el tipo de recompensa. 
                                                                        Por favor, inténtelo de nuevo.');
        }
    }

    public function update(Request $request) {
        try {
            $validatedData = $request->validate([
                'idTipoRecompensa' => 'required|exists:TiposRecompensas,idTipoRecompensa',
                'nombre_TipoRecompensa' => 'required|string',
                'descripcion_TipoRecompensa' => 'nullable|string',
                'colorTexto_TipoRecompensa' => 'required|string|max:7', // Formato hexadecimal
                'colorFondo_TipoRecompensa' => 'required|string|max:7',
            ]);

            DB::beginTransaction();

            $tipoRecompensaSolicitado = TipoRecompensa::find($validatedData['idTipoRecompensa']);
            $tipoRecompensaSolicitado->update([
                'nombre_TipoRecompensa' => $validatedData['nombre_TipoRecompensa'],
                'descripcion_TipoRecompensa' => $validatedData['descripcion_TipoRecompensa'],
                'colorTexto_TipoRecompensa' => $validatedData['colorTexto_TipoRecompensa'],
                'colorFondo_TipoRecompensa' => $validatedData['colorFondo_TipoRecompensa'],
            ]);

            // dd($tipoRecompensaSolicitado);

            $messageUpdate = 'Tipo de recompensa actualizado correctamente';
            
            DB::commit();
            return redirect()->route('tiposRecompensas.create')->with('successTipoRecompensaUpdate', $messageUpdate);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('tiposRecompensas.create')->withErrors('Ocurrió un error al intentar actualizar el tipo de recompensa. 
                                                                        Por favor, inténtelo de nuevo.');
        }
    }

    public function disable(Request $request) {
        try {
            $validatedData = $request->validate([
                'idTipoRecompensa' => 'required|exists:TiposRecompensas,idTipoRecompensa',
            ]);

            DB::beginTransaction();

            $tipoRecompensa = TipoRecompensa::find($validatedData['idTipoRecompensa']);
           
            if ($tipoRecompensa) {
                $tipoRecompensa->delete(); // Eliminar registro de la BD lógicamente
            }

            DB::commit();
            return redirect()->route('tiposRecompensas.create')->with('successTipoRecompensaDelete', 'Tipo de recompensa eliminado correctamente');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('tiposRecompensas.create')->withErrors('Ocurrió un error al intentar inhabilitar el tipo de recompensa. 
                                                                            Por favor, inténtelo de nuevo.');
        }
    }

    public function restore(Request $request) {
        try {
            $validatedData = $request->validate([
                'idTipoRecompensa' => 'required|exists:TiposRecompensas,idTipoRecompensa',
            ]);

            DB::beginTransaction();

            // Obtener la recompensa eliminada lógicamente
            $tipoRecompensa = TipoRecompensa::onlyTrashed()->where('idTipoRecompensa', $validatedData['idTipoRecompensa'])->first();
        
            if ($tipoRecompensa) {
                $tipoRecompensa->restore(); // Restaurar registro
            }

            DB::commit();
            return redirect()->route('tiposRecompensas.create')->with('successTipoRecompensaRestore', 'Tipo de recompensa eliminado correctamente');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('tiposRecompensas.create')->withErrors('Ocurrió un error al intentar restaurar el tipo de recompensa. 
                                                                            Por favor, inténtelo de nuevo.');
        }
    }

    public function delete(Request $request) {
        try {
            $validatedData = $request->validate([
                'idTipoRecompensa' => 'required|exists:TiposRecompensas,idTipoRecompensa',
            ]);

            DB::beginTransaction();

            $tipoRecompensa = TipoRecompensa::find($validatedData['idTipoRecompensa']);
           
            if ($tipoRecompensa) {
                $tipoRecompensa->forceDelete(); // Eliminar registro de la BD físicamente
            }

            DB::commit();
            return redirect()->route('tiposRecompensas.create')->with('successTipoRecompensaDelete', 'Tipo de recompensa eliminado correctamente');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('tiposRecompensas.create')->withErrors('Ocurrió un error al intentar eliminar el tipo de recompensa. 
                                                                            Por favor, inténtelo de nuevo.');
        }
    }

    public function returnArrayTiposRecompensas() {
        $tiposRecompensas = TipoRecompensa::all();
        $index = 1;

        $data = $tiposRecompensas->map(function ($tipoRecom) use (&$index) {
            return [
                'index' => $index++,
                'codigoTipoRecompensa' => $tipoRecom->codigoTipoRecompensa,
                'nombre_TipoRecompensa' => $tipoRecom->nombre_TipoRecompensa,
                'descripcion_TipoRecompensa' => $tipoRecom->descripcion_TipoRecompensa,
                'created_at' => $tipoRecom->created_at,
                'updated_at' => $tipoRecom->updated_at,
            ];
        });
        
        return $data->toArray();
    }

    public function exportarAllTiposRecompensasPDF()
    {
        try {
            $data = $this->returnArrayTiposRecompensas();

            // Verificar si hay datos para exportar
            if (count($data) === 0) {
                throw new \Exception("No hay datos disponibles para exportar la tabla de rangos.");
            }

            // Configurar los parámetros del PDF
            $paperSize = 'A4';
            $view = 'tables.tablaTiposRecompensasPDFA4';
            $fileName = "Club de técnicos DIMACOF-Listado de Tipos de Recompensas-" . $this->obtenerFechaHoraFormateadaExportaciones() . ".pdf";

            // Generar el PDF con los datos
            $pdf = Pdf::loadView($view, ['data' => $data])
                    ->setPaper($paperSize, 'landscape');

            // Retornar el PDF para visualizar o descargar
            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error("Error en exportarAllTiposRecompensasPDF: " . $e->getMessage());

            // Retornar una respuesta clara al usuario
            return response()->json([
                'message' => 'Ocurrió un error al generar el PDF.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Oficio;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpParser\Node\Expr\Throw_;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SystemNotificationController;

class OficioController extends Controller
{
    public function returnNuevoCodigoOficio() {
        $ultimoNumberOficioID = Oficio::max('idOficio');
        if (!$ultimoNumberOficioID) {
            return 'OFI-01';
        }
        $nuevoNumberOficioID = $ultimoNumberOficioID + 1;
        $nuevoCodigoOficio = 'OFI-'. str_pad($nuevoNumberOficioID, 2, '0', STR_PAD_LEFT);
        return $nuevoCodigoOficio;
    }

    public function create() {
        $oficios = Oficio::all(); 
        // Para depurar el códigoOficio
        //dd($oficios->pluck('codigoOficio'));
        //dd($oficios->pluck('codigoNombreOficio'));

        $nuevoIdOficio = Oficio::max('idOficio') ? Oficio::max('idOficio') + 1 : 1;
        $nuevoCodigoOficio = $this->returnNuevoCodigoOficio();
        $oficiosEliminados = Oficio::onlyTrashed()->get();

        // Obtener las notificaciones
        $notifications = SystemNotificationController::getActiveNotifications();
 
        return view('dashboard.oficios', compact('oficios', 'nuevoIdOficio', 'nuevoCodigoOficio', 'oficiosEliminados', 'notifications'));
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'idOficio' => 'required|numeric',
                'nombre_Oficio' => 'required|string',
                'descripcion_Oficio' => 'nullable|string',
            ]);

            $oficio = new Oficio($validatedData);
            $oficio->save();

            $messageStore = 'Recompensa guardada correctamente';
            return redirect()->route('oficios.create')->with('successOficioStore', $messageStore);
        } catch (\Exception $e) {
            return redirect()->route('oficios.create')->withErrors('Ocurrió un error al intentar crear el oficio. Por favor, inténtelo de nuevo.');
        }
    }

    public function update(Request $request) {
        $validatedData = $request->validate([
            'idOficio' => 'required|exists:Oficios,idOficio',
            'descripcion_Oficio' => 'required|string',
        ]);

        $oficioSolicitado = Oficio::find($validatedData['idOficio']);
        $oficioSolicitado->update([
            'descripcion_Oficio' => $validatedData['descripcion_Oficio'],
        ]);
        
        //dd($oficioSolicitado);

        $messageUpdate = 'Oficio actualizado correctamente';
        return redirect()->route('oficios.create')->with('successOficioUpdate', $messageUpdate);
    }

    public function disable(Request $request) {
        $validatedData = $request->validate([
            'idOficio' => 'required|exists:Oficios,idOficio',
        ]);

        // Encuentra la recompensa usando el idRecompensa
        $oficio = Oficio::where("idOficio", $validatedData['idOficio'])->first();
    
        // Verifica si se encontró la recompensa
        if ($oficio) {
            // Aplica soft delete
            $oficio->delete();
            $messageDisable = 'Oficio inhabilitado correctamente';
        } else {
            $messageDisable = 'Oficio no encontrado';
        }
    
        return redirect()->route('oficios.create')->with('successOficioDisable', $messageDisable);
    }

    public function restore(Request $request) {
        try {
            $validatedData = $request->validate([
                'idOficio' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();
            
            //dd($validatedData);

            $oficioEliminado = Oficio::onlyTrashed()->where('idOficio', $validatedData['idOficio'])->first();
            
            if (!$oficioEliminado) {
                return redirect()->route('oficios.create')->withErrors('Oficio no encontrado o ya restaurado.');
            }
            
            $oficioEliminado->restore();
            
            DB::commit();
            return redirect()->route('oficios.create')->with('successOficioRestore', 'Oficio restaurado correctamente.');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('oficios.create')->withErrors('Ocurrió un error al intentar restaurar el oficio. Por favor, inténtelo de nuevo.');
        }
    }

    public function delete(Request $request) {
        try {
            $validatedData = $request->validate([
                'idOficio' => 'required|exists:Oficios,idOficio',
            ]);
    
            // Encuentra el oficio
            $oficio = Oficio::findOrFail($validatedData['idOficio']);
    
            // Verifica si tiene técnicos asociados en la tabla intermedia
            if ($oficio->tecnicosOficios()->exists()) {
                return redirect()->route('oficios.create')->with('errorOficioDelete', 'El oficio no puede ser eliminado porque hay técnicos asociados.');
            }
    
            // Si no hay técnicos asociados, eliminar el oficio
            $oficio->forceDelete();
    
            return redirect()->route('oficios.create')->with('successOficioDelete', 'Oficio eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('oficios.create');
        }
    }

    public function tabla()
    {
        $oficios = Oficio::select([
            'idOficio', 
            'nombre_Oficio', 
            'descripcion_Oficio', 
            'created_at', 
            'updated_at',
        ])->get();

        // Agregar el índice y formatear las fechas
        $oficios = $oficios->map(function ($oficio, $index) {
            $oficio->orderNum = $index + 1;
            $oficio->created_at = Carbon::parse($oficio->created_at)->toDateTimeString();
            $oficio->updated_at = Carbon::parse($oficio->updated_at)->toDateTimeString();
            return $oficio;
        });

        return DataTables::make($oficios)->toJson();
    }

    public function returnArrayOficios() {
        $oficios = Oficio::all();
        $index = 1;

        $data = $oficios->map(function ($oficio) use (&$index) {
            return [
                'index' => $index++,
                'codigoOficio' => $oficio->codigoOficio,
                'nombre_Oficio' => $oficio->nombre_Oficio,
                'descripcion_Oficio' => $oficio->descripcion_Oficio,
                'created_at' => $oficio->created_at,
                'updated_at' => $oficio->updated_at,
            ];
        });
        
        return $data->toArray();
    }

    public function exportarAllOficiosPDF()
    {
        try {
            // Cargar datos de técnicos con oficios
            $data = $this->returnArrayOficios();

            // Verificar si hay datos para exportar
            if (count($data) === 0) {
                throw new \Exception("No hay datos disponibles para exportar la tabla de oficios.");
            }

            // Configurar los parámetros del PDF
            $paperSize = 'A4';
            $view = 'tables.tablaOficiosPDFA4';
            $fileName = "Club de técnicos DIMACOF-Listado de Oficios-" . $this->obtenerFechaHoraFormateadaExportaciones() . ".pdf";

            // Generar el PDF con los datos
            $pdf = Pdf::loadView($view, ['data' => $data])
                    ->setPaper($paperSize, 'landscape');

            // Retornar el PDF para visualizar o descargar
            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error("Error en exportarAllOficiosPDF: " . $e->getMessage());

            // Retornar una respuesta clara al usuario
            return response()->json([
                'message' => 'Ocurrió un error al generar el PDF.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

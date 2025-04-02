<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Recompensa;
use Illuminate\Http\Request;
use Illuminate\Auth\Recaller;
use App\Models\TipoRecompensa;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\TipoRecompensaController;
use App\Http\Controllers\SystemNotificationController;

class RecompensaController extends Controller
{   
    public function generarIdRecompensa()
    {
        // Obtener el último ID de recompensa ordenado de forma descendente
        //$ultimaRecompensa = DB::table('recompensas')->orderBy('idRecompensa', 'desc')->first();
        $ultimaRecompensaID = Recompensa::max('idRecompensa');

        // Si la tabla está vacía, comenzar desde "RECOM-001"
        if (!$ultimaRecompensaID) {
            return 'RECOM-001';
        }

        // Extraer el número de la cadena del último ID de recompensa
        $strNumRecompensa = substr($ultimaRecompensaID, -3); // Obtiene los últimos 3 caracteres

        // Convertir la parte numérica a entero
        $intNumRecompensa = intval($strNumRecompensa);
        
        // Incrementar el número para generar el siguiente idRecompensa
        $nuevoNumero = $intNumRecompensa + 1;

        // Formatear el nuevo número con ceros a la izquierda
        $nuevoIdRecompensa = 'RECOM-'. str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);
        
        return $nuevoIdRecompensa;
    }

    public function returnArrayNombresTiposRecompensas()
    {
        // Obtener todos los tipos de recompensas y filtrar
        return TipoRecompensa::all()
                ->reject(function ($tipoRecompensa) {
                    return $tipoRecompensa->idTipoRecompensa == 1;
                })
                ->pluck('nombre_TipoRecompensa')
                ->values(); // Reindexar
    }

    public function create()
    {
        // Obtener la última recompensa para generar el nuevo ID
        $idNuevaRecompensa = $this->generarIdRecompensa();
        $idNuevoTipoRecompensa = TipoRecompensaController::returnNuevoCodigoTipoRecompensa();

        // Obtener todas las recompensas activas (Inicialmente "RECOM-000, Efectivo" está inactivo)
        $recompensas = Recompensa::query()
                                ->join('TiposRecompensas', 'Recompensas.idTipoRecompensa', '=', 'TiposRecompensas.idTipoRecompensa')
                                ->select(['Recompensas.*', 'TiposRecompensas.nombre_TipoRecompensa',
                                        'TiposRecompensas.colorTexto_TipoRecompensa', 'TiposRecompensas.colorFondo_TipoRecompensa' ])
                                ->whereNull('Recompensas.deleted_at')
                                ->orderBy('Recompensas.idRecompensa', 'ASC') 
                                ->get(); 

        // Obtener todas las recompensas no activas (soft deleted) con sus tipos
        $recompensasEliminadas = Recompensa::onlyTrashed()
                                            ->join('TiposRecompensas', 'Recompensas.idTipoRecompensa', '=', 'TiposRecompensas.idTipoRecompensa')
                                            ->select(['Recompensas.*', 'TiposRecompensas.nombre_TipoRecompensa',
                                                    'TiposRecompensas.colorTexto_TipoRecompensa', 'TiposRecompensas.colorFondo_TipoRecompensa' ])
                                            ->orderBy('Recompensas.idRecompensa', 'ASC') 
                                            ->get();

        $tiposRecompensas = TipoRecompensa::all()->reject(function ($recompensa) {
            return $recompensa->idTipoRecompensa == 1;
        })->values(); // Reindexa los índices de la colección

        // dd($tiposRecompensas->pluck('codigoTipoRecompensa')); 

        $nombresTiposRecompensas = $this->returnArrayNombresTiposRecompensas();

        //dd($nombresTiposRecompensas);

        // Obtener las notificaciones
        $notifications = SystemNotificationController::getActiveNotifications();


        return view('dashboard.recompensas', compact('recompensas', 'tiposRecompensas', 'idNuevaRecompensa', 
                                                    'idNuevoTipoRecompensa', 'recompensasEliminadas', 'nombresTiposRecompensas',
                                                    'notifications'));
    }
    
    public function store(Request $request) 
    {
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'tipoRecompensa' => 'required|string',
                'descripcionRecompensa' => 'required|string|max:255',
                'costoPuntos_Recompensa' => 'required|numeric|min:0',
                'stock_Recompensa' => 'required|numeric|min:1',
            ]);

            Controller::$newNotifications = false;

            DB::beginTransaction();

            // Verificar si el tipo de recompensa existe
            $idTipoRecompensa = TipoRecompensa::where('nombre_TipoRecompensa', $validatedData['tipoRecompensa'])->pluck('idTipoRecompensa')->first();
            if (!$idTipoRecompensa) {
                return redirect()->route('recompensas.create')
                    ->withErrors(['tipoRecompensa' => 'El tipo de recompensa seleccionado no existe.']);
            }

            // Generar ID de la nueva recompensa
            $idNuevaRecompensa = $this->generarIdRecompensa();

            // Verificar si ya existe una recompensa con la misma descripción
            $existeRecompensa = Recompensa::where('descripcionRecompensa', $validatedData['descripcionRecompensa'])->exists();
            if ($existeRecompensa) {
                return redirect()->route('recompensas.create')
                    ->withErrors(['descripcionRecompensa' => 'Ya existe una recompensa con esta descripción.']);
            }

            // Crear los datos de recompensa
            $recompensaData = [
                'idRecompensa' => $idNuevaRecompensa,
                'idTipoRecompensa' => $idTipoRecompensa,
                'descripcionRecompensa' => $validatedData['descripcionRecompensa'],
                'costoPuntos_Recompensa' => $validatedData['costoPuntos_Recompensa'],
                'stock_Recompensa' => $validatedData['stock_Recompensa'],
            ];

            // Crear recompensa en la base de datos
            Recompensa::create($recompensaData);

            // Si el stock de la recompensa es menor o igual a 15, crear una notificación
            if ($recompensaData['stock_Recompensa'] <= config('settings.unidadesRestantesRecompensasNotificacion')) {
                SystemNotification::create([
                    'icon' => 'timer',
                    'tblToFilter' => 'tblRecompensas',
                    'title' => 'Recompensa a punto de agotar stock',
                    'item' => $recompensaData['idRecompensa'] . ' | ' . $recompensaData['descripcionRecompensa'],
                    'description' => 'tiene ' . config('settings.unidadesRestantesRecompensasNotificacion'). ' o menos unidades restantes',
                    'routeToReview' => 'recompensas.create',
                ]);
                Controller::$newNotifications = true;
            }
            
            DB::commit();
            
            $messageStore = 'Recompensa guardada correctamente.';

            return Controller::$newNotifications
                ? redirect()->route('recompensas.create')->with('successRecompensaStore', $messageStore)
                    ->with('newNotifications', '-')
                : redirect()->route('recompensas.create')->with('successRecompensaStore', $messageStore);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('recompensas.create')
                ->withErrors('Ocurrió un error al guardar la recompensa: ' . $e->getMessage());
        }
    }

    public function update(Request $request) 
    {
        $recompensaSolicitada = Recompensa::find($request->idRecompensa);

        Controller::$newNotifications = false;

        // Actualizar los campos
        $recompensaSolicitada->update([
            'costoPuntos_Recompensa' => $request->costoPuntos_Recompensa,
            'stock_Recompensa' => $request->stock_Recompensa,
        ]);

        // Si el stock de la recompensa es menor o igual a 15, crear una notificación
        if ($recompensaSolicitada['stock_Recompensa'] <= config('settings.unidadesRestantesRecompensasNotificacion')) {
            SystemNotification::create([
                'icon' => 'timer',
                'tblToFilter' => 'tblRecompensas',
                'title' => 'Recompensa a punto de agotar stock',
                'item' => $recompensaSolicitada->idRecompensa . ' | ' . $recompensaSolicitada->descripcionRecompensa,
                'description' => 'tiene ' . config('settings.unidadesRestantesRecompensasNotificacion'). ' o menos unidades restantes',
                'routeToReview' => 'recompensas.create',
            ]);
            Controller::$newNotifications = true;
        }

        $messageUpdate = 'Recompensa actualizada correctamente';

        return Controller::$newNotifications
            ? redirect()->route('recompensas.create')->with('successRecompensaStore', $messageUpdate)
                ->with('newNotifications', '-')
            : redirect()->route('recompensas.create')->with('successRecompensaStore', $messageUpdate);
    }

    public function disable(Request $request) 
    {
        // Encuentra la recompensa usando el idRecompensa
        $recompensa = Recompensa::where("idRecompensa", $request->idRecompensa)->first();
    
        // Verifica si se encontró la recompensa
        if ($recompensa) {
            if ($recompensa->solicitudesCanjesRecompensas()->exists()) {
                return redirect()->route('recompensas.create')->with('errorRecompensaDisable', 'La recompensa no puede ser inhabilitada porque hay solicitudes de canje asociadas.');
            }

            // Aplica soft delete
            $recompensa->delete();
    
            $messageDelete = 'Recompensa eliminada correctamente';
        } else {
            $messageDelete = 'Recompensa no encontrada';
        }
    
        return redirect()->route('recompensas.create')->with('successRecompensaDisable', $messageDelete);
    }

    public function restore(Request $request) {
        try {
            $validatedData = $request->validate([
                'idRecompensa' => 'required|string|max:9',
            ]);
            
            // Iniciar transacción
            DB::beginTransaction();
            
            //dd($validatedData['idRecompensa']);

            // Obtener la recompensa eliminada lógicamente
            $recompensaEliminada = Recompensa::onlyTrashed()->where('idRecompensa', $validatedData['idRecompensa'])->first();
            
            if (!$recompensaEliminada) {
                // Recompensa no encontrada o ya existe en registros activos
                return redirect()->route('recompensas.create')->withErrors('Recompensa no encontrada o ya restaurada.');
            }
            
            // Restaurar la recompensa
            $recompensaEliminada->restore();
            
            // Confirmar transacción
            DB::commit();
            return redirect()->route('recompensas.create')->with('successRecompensaRestaurada', 'Recompensa restaurada correctamente.');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            
            return redirect()->route('recompensas.create')->withErrors('Ocurrió un error al intentar restaurar la recompensa. Por favor, inténtelo de nuevo.');
        }
    }

    public function delete(Request $request) {
        try {

            $validatedData = $request->validate([
                'idRecompensa' => 'required|exists:Recompensas,idRecompensa',
            ]);
    
            $recompensa = Recompensa::findOrFail($validatedData['idRecompensa']);
            

            if ($recompensa->canjesRecompensas()->exists() || $recompensa->solicitudesCanjesRecompensas()->exists()) {
                return redirect()->route('recompensas.create')->with('errorRecompensaDelete', 'La recompensa no puede ser eliminada porque hay canjes ó solicitudes de canje asociados.');
            }
            
            $recompensa->forceDelete();
    
            return redirect()->route('recompensas.create')->with('successRecompensaDelete', 'Recompensa eliminada correctamente');
        } catch (\Exception $e) {
            return redirect()->route('recompensas.create');
        }
    }

    public static function updateStockByIdRecompensaCantidad($idRecompensa, $cantidad) {
        $recompensa = Recompensa::where('idRecompensa', $idRecompensa)->first();
    
        if ($recompensa) {
            $stockActual = self::calcStockActualByIdRecompensa($idRecompensa);
            $nuevoStock = $stockActual - $cantidad;
    
            $recompensa->update([
                'stock_Recompensa' => $nuevoStock,
            ]);

            // Si el stock de la recompensa es menor o igual a 15, crear una notificación
            if ($recompensa['stock_Recompensa'] <= config('settings.unidadesRestantesRecompensasNotificacion')) {
                SystemNotification::create([
                    'icon' => 'timer',
                    'tblToFilter' => 'tblRecompensas',
                    'title' => 'Recompensa a punto de agotar stock',
                    'item' => $recompensa['idRecompensa'] . ' | ' . $recompensa['descripcionRecompensa'],
                    'description' => 'tiene ' . config('settings.unidadesRestantesRecompensasNotificacion'). ' o menos unidades restantes',
                    'routeToReview' => 'recompensas.create',
                ]);
                Controller::$newNotifications = true;
            }
        } else {
            // Manejar el caso en el que la recompensa no se encuentra
            throw new Exception("Recompensa no encontrada con id: {$idRecompensa}");
        }
    }
    
    public static function calcStockActualByIdRecompensa($idRecompensa) {
        // Obtener solo el valor de stock_Recompensa
        $recompensa = Recompensa::where('idRecompensa', $idRecompensa)->first();
        
        if ($recompensa) {
            //dd($recompensa->stock_Recompensa);
            return $recompensa->stock_Recompensa;
        } else {
            throw new Exception("Recompensa no encontrada con id: {$idRecompensa}");
        }
    }

    public function returnArrayRecompensas() {
        $recompensas = Recompensa::all();
        $index = 1;

        // Mapear los datos para transformarlos
        $data = $recompensas->map(function ($recompensa) use (&$index) {
            return [
                'index' => $index++,
                'idRecompensa' => $recompensa->idRecompensa,
                'idTipoRecompensa' => $recompensa->idTipoRecompensa,
                'nombre_TipoRecompensa' => $recompensa->nombre_TipoRecompensa,
                'descripcionRecompensa' => $recompensa->descripcionRecompensa,
                'costoPuntos_Recompensa' => $recompensa->costoPuntos_Recompensa,
                'stock_Recompensa' => $recompensa->stock_Recompensa,
                'created_at' => $recompensa->created_at,
                'updated_at' => $recompensa->updated_at,
            ];
        });
        
        return $data->toArray();
    }

    public function exportarAllRecompensasPDF()
    {
        try {
            // Cargar datos de técnicos con oficios
            $data = $this->returnArrayRecompensas();

            // Verificar si hay datos para exportar
            if (count($data) === 0) {
                throw new \Exception("No hay datos disponibles para exportar la tabla de recompensas.");
            }

            // Configurar los parámetros del PDF
            $paperSize = 'A4'; // Tamaño del papel
            $view = 'tables.tablaRecompensasPDFA4'; // Vista para generar el PDF
            $fileName = "Club de técnicos DIMACOF-Listado de Recompensas-" . $this->obtenerFechaHoraFormateadaExportaciones() . ".pdf";

            // Generar el PDF con los datos
            $pdf = Pdf::loadView($view, ['data' => $data])
                    ->setPaper($paperSize, 'landscape'); // Configurar tamaño y orientación

            // Retornar el PDF para visualizar o descargar
            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error("Error en exportarAllRecompensasPDF: " . $e->getMessage());

            // Retornar una respuesta clara al usuario
            return response()->json([
                'message' => 'Ocurrió un error al generar el PDF.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Tecnico;
use App\Models\EstadoVenta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SolicitudesCanje;
use Yajra\DataTables\DataTables;
use App\Models\VentaIntermediada;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\TecnicoController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VentaIntermediadaController extends Controller
{
    public function limpiarIDs($id)
    {
        // Dividir la cadena usando el guion '-' como delimitador
        $partes = explode('-', $id);
        
        if (count($partes) < 2) {
            return $id;  // Devuelve el ID original si el formato es inválido
        }

        // Obtener el último elemento del array $partes, que sería '00xx'
        $numero = end($partes);

        // Eliminar los ceros delante del número
        $numeroLimpio = ltrim($numero, '0');

        // Construir el nuevo ID con los ceros eliminados
        $idLimpio = $partes[0] . '-'  . $numeroLimpio; // F001-72

        return $idLimpio;
    }

    public function detectarTipoComprobante($id) 
    {
        $tipoComprobante = '';

        if (strpos($id, 'F') !== false) {
            $tipoComprobante = 'FACTURA ELECTRÓNICA';
        } elseif (strpos($id, 'B') !== false) {
            $tipoComprobante = 'BOLETA DE VENTA ELECTRÓNICA';
        }

        return $tipoComprobante;
    }
    
    public static function updateStateVentaIntermediada($idVentaIntermediada, $nuevosPuntosActuales) {
        $venta = VentaIntermediada::findOrFail($idVentaIntermediada);
    
        // Calcula el nuevo estado de la venta
        $idEstado = self::returnStateIdVentaIntermediada($idVentaIntermediada, $nuevosPuntosActuales);
    
        // Actualiza los datos en la base de datos
        $venta->update([
            'puntosActuales_VentaIntermediada' => $nuevosPuntosActuales,
            'idEstadoVenta' => $idEstado,
        ]);
    
        return $idEstado;
    }
    
    public function agregarCamposExtraVentas($ventas) {
        foreach ($ventas as $venta) {
            $venta->tipoComprobante = $this->detectarTipoComprobante($venta->idVentaIntermediada);
            $venta->diasTranscurridos = $this->returnDiasTranscurridosHastaHoy($venta->fechaHoraEmision_VentaIntermediada);
    
            // Actualiza el estado de la venta
            $idEstado = self::updateStateVentaIntermediada($venta->idVentaIntermediada, $venta->puntosActuales_VentaIntermediada);
            $nombreEstado = $this->getNombreEstadoVentaIntermediadaActualById($venta->idVentaIntermediada);
    
            // Enriquecer la venta con nuevos datos
            $venta->idEstadoVenta = $idEstado;
            $venta->nombre_EstadoVenta = $nombreEstado;
        }
        return $ventas;
    } 

    public function obtenerVentasIntermediadas() {
        // Consulta y procesamiento de datos
        $ventasIntermediadas = VentaIntermediada::query()
            ->leftJoin('Canjes', 'VentasIntermediadas.idVentaIntermediada', '=', 'Canjes.idVentaIntermediada')
            ->join('EstadoVentas', 'VentasIntermediadas.idEstadoVenta', '=', 'EstadoVentas.idEstadoVenta')
            ->select([
                'VentasIntermediadas.*',
                'Canjes.fechaHora_Canje as fechaHora_Canje',
                'Canjes.puntosRestantes_Canje as puntosRestantes_Canje',
                'EstadoVentas.nombre_EstadoVenta as nombre_EstadoVenta'
            ])
            ->get()
            ->groupBy('idVentaIntermediada')
            ->map(fn($grupo) => $grupo->sortByDesc('fechaHora_Canje')->first());
    
        // Enriquecer las ventas con datos adicionales
        return $this->agregarCamposExtraVentas($ventasIntermediadas);
    }

    public function returnArrayVentasIntermediadasTabla() {
        // Obtener las ventas intermediadas con los datos relacionados
        $ventasIntermediadas = VentaIntermediada::with(['canjes', 'estadoVenta'])->get();
        // protected $appends = ['tipoComprobante' , 'diasTranscurridos'];

        $index = 1;
    
        // Mapear las ventas para estructurarlas
        $data = $ventasIntermediadas->map(function ($venta) use (&$index) {

            $ultimoCanje = $venta->canjes->sortByDesc('fechaHora_Canje')->first();

            return [
                'index' => $index++,
                'idVentaIntermediada' => $venta->idVentaIntermediada,
                'tipoComprobante' => $venta->tipoComprobante,
                'fechaHoraEmision_VentaIntermediada' => $venta->fechaHoraEmision_VentaIntermediada,
                'fechaHoraCargada_VentaIntermediada' => $venta->fechaHoraCargada_VentaIntermediada,
                'nombreCliente_VentaIntermediada' => $venta->nombreCliente_VentaIntermediada,
                'tipoCodigoCliente_VentaIntermediada' => $venta->tipoCodigoCliente_VentaIntermediada,
                'codigoCliente_VentaIntermediada' => $venta->codigoCliente_VentaIntermediada,
                'montoTotal_VentaIntermediada' => "S/. " . $venta->montoTotal_VentaIntermediada,
                'puntosGanados_VentaIntermediada' => $venta->puntosGanados_VentaIntermediada,
                'nombreTecnico' => $venta->nombreTecnico,
                'idTecnico' => $venta->idTecnico,
                'fechaHora_Canje' => $ultimoCanje?->fechaHora_Canje ?? 'Sin canje',
                'diasTranscurridos' => $venta->diasTranscurridos,

                'idEstadoVenta' => $venta->idEstadoVenta,
                'nombre_EstadoVenta' => $venta->estadoVenta?->nombre_EstadoVenta ?? 'Estado desconocido',

                // Campos compuestos
                'idVentaIntermediada_tipoComprobante' => $venta->idVentaIntermediada . " " . $venta->tipoComprobante,
                'nombreCliente_TipoCodigo_Codigo' => $venta->nombreCliente_VentaIntermediada . " " . 
                                                    $venta->tipoCodigoCliente_VentaIntermediada . ": " . $venta->codigoCliente_VentaIntermediada,
                'nombreTecnico_idTecnico' => $venta->nombreTecnico . " DNI: " . $venta->idTecnico,
            ];
        });
    
        return $data->toArray();
    }
    
    public function tabla(Request $request) {
        try {
            if ($request->ajax()) {
                $ventas = $this->returnArrayVentasIntermediadasTabla();

                if (empty($ventas)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No se encontraron ventas intermediadas.'
                    ], 204);
                }
                
                return DataTables::of($ventas)->make(true);
            }
        
            return abort(403);
        } catch (\Exception $e) {
            Log::info('Error en tabla VentaIntermediadaController', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function create() {
        try {
            //$ventas = $this->obtenerVentasIntermediadas();
            //dd($ventas->pluck('fechaVenta', 'idVentaIntermediada'));
            //dd($ventas->pluck('horaVenta', 'idVentaIntermediada'));
            //dd($ventas->pluck('fechaCargada', 'idVentaIntermediada'));
            //dd($ventas->pluck('horaCargada', 'idVentaIntermediada'));
            $ventas = $this->returnArrayVentasIntermediadasTabla();
            dd($ventas);

            $tecnicoController = new TecnicoController();
            $idsNombresOficios = $tecnicoController->returnArrayIdsNombresOficios(); 

            return view('dashboard.ventasIntermediadas', compact('idsNombresOficios'));
        } catch (\Exception $e) {
            dd("Error al mostrar las ventas intermediadas: " . $e->getMessage());
        }
    }
    
    function store(Request $request) {
        $validatedData = $request->validate([
            'idVentaIntermediada' => 'required|string|unique:VentasIntermediadas,idVentaIntermediada',
            'idTecnico' => 'required|exists:Tecnicos,idTecnico',
            'nombreTecnico' => 'required|string',
            'tipoCodigoCliente_VentaIntermediada' => 'required|string',
            'codigoCliente_VentaIntermediada' => 'required|string',
            'nombreCliente_VentaIntermediada' => 'required|string',
            'fechaHoraEmision_VentaIntermediada' => 'required|string',
            'montoTotal_VentaIntermediada' => 'required|numeric',
            'puntosGanados_VentaIntermediada' => 'required|numeric',
        ]);

        // Iniciar una transacción
        DB::transaction(function () use ($validatedData) {
            // Crear la venta intermediada
            $venta = VentaIntermediada::create(array_merge($validatedData, [
                'puntosActuales_VentaIntermediada' => $validatedData['puntosGanados_VentaIntermediada'], // Al inicio tiene todos sus puntos
            ]));

            // Actualizar los puntos actuales del técnico
            TecnicoController::updatePuntosActualesTecnicoById($venta['idTecnico']); // Llamado estático
            // Actualizar los puntos históricos del técnico
            TecnicoController::updatePuntosHistoricosTecnicoById($venta['idTecnico']); // Llamado estático
        });

        return redirect()->route('ventasIntermediadas.create')->with('successVentaIntermiadaStore', 'Venta Intermediada guardada correctamente');
    }

    public function delete(Request $request) {
        try {
            // Validación de la solicitud
            $validatedData = $request->validate([
                'idVentaIntermediada' => 'required|string|exists:VentasIntermediadas,idVentaIntermediada',
            ]);

            // Obtén la venta a eliminar
            $venta = VentaIntermediada::find($validatedData['idVentaIntermediada']);

            // Elimina la venta forzosamente
            $venta->forceDelete();

            // Redirige con mensaje de éxito
            return redirect()->route('ventasIntermediadas.create')
                            ->with('successVentaIntermiadaDelete', 'Venta Intermediada eliminada correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1]; // Código de error SQL

            if ($errorCode == 1451) { // Error 1451: Restricción de clave foránea
                return redirect()->route('ventasIntermediadas.create')
                                ->withErrors(['errorClaveForanea' => 'No se pudo eliminar esta venta intermediada porque está vinculada a 
                                                                    canjes o solicitudes canjes.']);
            }

            // Manejo de otros errores de base de datos
            return redirect()->route('ventasIntermediadas.create')
                            ->withErrors(['error' => 'Ocurrió un error inesperado al intentar eliminar la venta intermediada.']);
        }
    }

    public static function getIDEstadoVentaIntermediadaActualById($idVentaIntermediada) {
        try {
            $venta= VentaIntermediada::findOrFail($idVentaIntermediada);
            $idEstadoVenta = VentaIntermediadaController::returnStateIdVentaIntermediada($idVentaIntermediada, $venta->puntosActuales_VentaIntermediada);
            return $idEstadoVenta;
        } catch (\Exception $e) {
            dd("Error en getEstadoVentaIntermediadaActualById: " . $e->getMessage());
        }
    }

    public static function getNombreEstadoVentaIntermediadaActualById($idVentaIntermediada) {
        try {
            $venta= VentaIntermediada::findOrFail($idVentaIntermediada);
            $idEstadoVenta = VentaIntermediadaController::returnStateIdVentaIntermediada($idVentaIntermediada, $venta->puntosActuales_VentaIntermediada);
            $nombreEstadoVenta = EstadoVenta::where('idEstadoVenta', $idEstadoVenta)->first()->nombre_EstadoVenta;
            return $nombreEstadoVenta;
        } catch (\Exception $e) { // Corrección del error y tipo específico de excepción
            dd("Error en getEstadoVentaIntermediadaActualById: " . $e->getMessage()); // Muestra el mensaje de error en caso de falla
        }
    }

    public static function returnStateIdVentaIntermediada($idVentaIntermediada, $nuevosPuntosActuales)
    {
        try {
            $venta = VentaIntermediada::findOrFail($idVentaIntermediada);
            $diasTranscurridos = Controller::returnDiasTranscurridosHastaHoy($venta->fechaHoraEmision_VentaIntermediada);
            $maxdaysCanje = env('MAXDAYS_CANJE', 90);

           
            // Validar venta con estado Tiempo agotado
            if ($diasTranscurridos > $maxdaysCanje && $nuevosPuntosActuales != 0) {
                return 4; 
            }

            // Validar venta con estado Redimido (completo)
            if ($nuevosPuntosActuales == 0 && $diasTranscurridos <= $maxdaysCanje) {
                return 3;
            }

            // Validar venta con estado En espera
            if ($nuevosPuntosActuales == $venta->puntosGanados_VentaIntermediada && $diasTranscurridos <= $maxdaysCanje) {
                // Validar venta con estado En espera (solicitado desde app)
                $apareceEnSolicitud = VentaIntermediada::where('idVentaIntermediada', $venta->idVentaIntermediada)
                                    ->pluck('apareceEnSolicitud')
                                    ->first();

                if ($apareceEnSolicitud == 1) {
                    // Log::info("En espera (solicitado desde app) → " . $venta->idVentaIntermediada);
                    return 5;
                }

                return 1;
            }

            // Validar venta con estado Redimido (parcial)
            if ($nuevosPuntosActuales > 0 && $diasTranscurridos <= $maxdaysCanje) {
                return 2;  
            }
        } catch (ModelNotFoundException $e) {
            Log::error("VentaIntermediada no encontrada: " . $idVentaIntermediada);
            return -1; // Código de error para venta no encontrada
        } catch (Exception $e) {
            Log::info("Probablemente no exista un estado para esta venta:" . "\n" . "Venta: " . $idVentaIntermediada . "\n" .
                    "Días transcurridos: " . $diasTranscurridos . "\n" .
                    "Puntos actuales: " . $nuevosPuntosActuales);

            Log::error("Error en returnStateIdVentaIntermediada: " . $e->getMessage());
            return -2; // Código de error genérico
        }
    }
 
    public function exportarAllVentasPDF()
    {
        try {
            // Cargar datos de técnicos con oficios
            $data = $this->returnArrayVentasIntermediadasTabla();

            // Verificar si hay datos para exportar
            if (count($data) === 0) {
                throw new \Exception("No hay datos disponibles para exportar la tabla de ventas intermediadas.");
            }

            // Configurar los parámetros del PDF
            $paperSize = 'A4'; // Tamaño del papel
            $view = 'tables.tablaVentasPDFA4'; // Vista para generar el PDF
            $fileName = "Club de técnicos DIMACOF-Listado de Ventas Intermediadas-" . $this->obtenerFechaHoraFormateadaExportaciones() . ".pdf";

            // Generar el PDF con los datos
            $pdf = Pdf::loadView($view, ['data' => $data])
                    ->setPaper($paperSize, 'landscape'); // Configurar tamaño y orientación

            // Retornar el PDF para visualizar o descargar
            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error("Error en exportarAllVentasPDF: " . $e->getMessage());

            // Retornar una respuesta clara al usuario
            return response()->json([
                'message' => 'Ocurrió un error al generar el PDF.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // FETCH
    public function getComprobantesEnEsperaByIdTecnico($idTecnico)
    {
        // Validar que el técnico existe (opcional)
        if (!Tecnico::find($idTecnico)) {
            return response()->json(['error' => 'Técnico no encontrado'], 404);
        }

        // Obtener las ventas intermediadas no asociadas con alguna solicitud de canje
        $comprobantes = VentaIntermediada::with('estadoVenta') // Cargar relación de estado
                                        ->doesntHave('solicitudesCanje') // Filtrar comprobantes sin solicitudes de canje
                                        ->where('idTecnico', $idTecnico)
                                        ->whereIn('idEstadoVenta', [1, 2]) // Estados: En espera o Redimido (parcial)
                                        ->get();

        return response()->json($comprobantes);
    }

    public function verificarExistenciaVenta(Request $request) {
        try {
            // Validar entrada
            $validated = $request->validate([
                'idVentaIntermediada' => 'required|string|size:13'
            ]);
            

            // Verificar existencia de la venta
            $exists = VentaIntermediada::where('idVentaIntermediada', $validated['idVentaIntermediada'])->exists();
            
            return response()->json(['exists' => $exists]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validación fallida', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::info('Error en verificarExistenciaVenta', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error al verificar la venta intermediada', 'details' => $e->getMessage()], 500);
        }
    }

    public function getVentaByIdVentaIdNombreTecnico(Request $request) {
        try {
            $idVentaIdNombreTecnico = $request->input('idVentaIdNombreTecnico', '');
            
            // Validar si el filtro está vacío
            if (empty($idVentaIdNombreTecnico)) {
                return response()->json(['message' => 'No se ingresó un idVentaIdNombreTecnico.',
                ], 404);
            }

            // Validar si el valor contiene el formato 'idVentaIntermediada | idTecnico - nombreTecnico'
            if (!preg_match('/^[BF][0-9]{3}-[0-9]{8} \| \d{8} - .+$/', $idVentaIdNombreTecnico)) {
                return response()->json([
                    'message' => 'Formato incorrecto. El formato debe ser "idVentaIntermediada | idTecnico - nombreTecnico".',
                ], 404);
            }
            
            // Separar el valor en 'idVentaIntermediada', 'idTecnico', y 'nombreTecnico'
            list($idVentaIntermediada, $idTecnicoNombreTecnico) = explode(' | ', $idVentaIdNombreTecnico);
            list($idTecnico, $nombreTecnico) = explode(' - ', $idTecnicoNombreTecnico);
        
            // Buscar al técnico por id y nombre
            $ventaBuscada = VentaIntermediada::where('idVentaIntermediada', $idVentaIntermediada)->first();
            $idTecnicoBuscado = Tecnico::where('idTecnico', $idTecnico)->first();
            $nombreTecnicoBuscado = Tecnico::where('nombreTecnico', $nombreTecnico)->first();

            if ($ventaBuscada && $idTecnicoBuscado && $nombreTecnicoBuscado) {
                return response()->json(['ventaBuscada' => $ventaBuscada], 200);
            }
           
            return response()->json(['message' => 'Venta no encontrada'], 404);
        } catch (\Exception $e) {
            // Registrar detalles del error para depuración
            /* Log::info('Error en getTecnicoByIdNombre', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]); */
            
            // Responder con mensaje de error detallado
            return response()->json([
                'message' => 'Ocurrió un error en el servidor. Por favor intente nuevamente más tarde.',
                'error_details' => $e->getMessage()  // Agregar detalles del error si es necesario
            ], 500);
        }
    }

    public function getPaginatedVentas(Request $request) {
        try {
            $currentPage = $request->input('page', 1); // Por defecto, carga la página 1
            $pageSize = $request->input('pageSize', 6); // Por defecto devuelve 6 registros

            // Establecer la página actual en la paginación
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            
            // Paginar los resultados
            $ventasPaginatedDB = VentaIntermediada::paginate($pageSize);

            // Verificar si se encontraron ventas
            if ($ventasPaginatedDB->total() === 0) {
                return response()->json([
                    'data' => null,
                    'message' => 'No hay ventas registradas aún',
                ], 200);
            }

            // Controller::printJSON($ventasPaginatedDB->items());

            return response()->json([
                'data' => $ventasPaginatedDB->items(),
                'current_page' => $ventasPaginatedDB->currentPage(),
                'last_page' => $ventasPaginatedDB->lastPage(),
                'total' => $ventasPaginatedDB->total(),
            ], 200);
        } catch (\Exception $e) {
            Log::info('Error en getPaginatedVentas', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error al obtener las ventas paginadas', 'details' => $e->getMessage()], 500);
        }
    }

    public function getFilteredVentas(Request $request) {
        try {
            $filter = $request->input('filter', '');

            // Construir la consulta utilizando Eloquent de manera segura
            $query = VentaIntermediada::query();
    
            $query->whereRaw("CONCAT(idVentaIntermediada, ' | ', idTecnico, ' - ', nombreTecnico) LIKE ?", ["%$filter%"]);

            // Obtener los resultados paginados
            $ventasDB = $query->paginate(6);

            // Verificar si hay resultados
            if ($ventasDB->isEmpty()) {
                return response()->json([
                    'data' => [],
                    'message' => 'No se encontraron ventas.',
                ], 404);
            }

            return response()->json([
                'data' => $ventasDB->items(),
                'current_page' => $ventasDB->currentPage(),
                'total' => $ventasDB->total(),
                'last_page' => $ventasDB->lastPage(),
                'per_page' => $ventasDB->perPage(),
            ], 200);
        } catch (\Exception $e) {
            /* // Registrar detalles del error para depuración
                Log::info('Error en getFilteredVentas', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]); */

            return response()->json([
                'message' => 'Error al filtrar ventas: ' . $e->getMessage(),
            ], 500);
        }
    }
}

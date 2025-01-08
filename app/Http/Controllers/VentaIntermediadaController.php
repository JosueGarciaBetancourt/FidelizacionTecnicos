<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Tecnico;
use App\Models\EstadoVenta;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\VentaIntermediada;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\TecnicoController;

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

    public static function returnStateIdVentaIntermediada($idVentaIntermediada, $nuevosPuntosActuales) {
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
            return 1;
        }

        // Validar venta con estado Redimido (parcial)
        if ($nuevosPuntosActuales > 0 && $diasTranscurridos <= $maxdaysCanje) {
            return 2;  
        }

        /*
            [   
                //'idEstadoVenta' => 1,
                'nombre_EstadoVenta' => 'En espera', //1 a 90 días transcurridos y puntos ganados sean iguales a los puntos actuales
            ],
            [
                //'idEstadoVenta' => 2,
                'nombre_EstadoVenta' => 'Redimido (parcial)', // Mínimo un canje asociado a la venta
            ],
            [
                //'idEstadoVenta' => 3,
                'nombre_EstadoVenta' => 'Redimido (completo)', // Canjear todos los puntos dentro de los 90 días
            ],
            [
                 //'idEstadoVenta' => 4,
                 'nombre_EstadoVenta' => 'Tiempo Agotado', // Tiene que ser una venta En espera ó Redimido (parcial) y supera los 90 días
            ],
        */
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
}

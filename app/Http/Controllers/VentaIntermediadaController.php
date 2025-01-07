<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use App\Models\EstadoVenta;
use Illuminate\Http\Request;
use App\Models\VentaIntermediada;
use App\Http\Controllers\TecnicoController;
use Exception;
use Illuminate\Support\Facades\DB;

class VentaIntermediadaController extends Controller
{
    public function algo() {
        return "algo";
    }

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

    /*public function create()
    {
        // Obtener todas las ventas intermediadas con sus canjes y cargar los modelos relacionados
        $ventasIntermediadas = VentaIntermediada::with('canjes')->get();

        //Log::info('ventasIntermediadas: ' . $ventasIntermediadas);

        $ventas = $ventasIntermediadas->map(function ($venta) {

            // Limpiar id
            $idLimpio = $this->limpiarIDs($venta->idVentaIntermediada);

            // Obtener tipo de comprobante
            $tipoComprobante = $this->detectarTipoComprobante($venta->idVentaIntermediada);

            // Obtener todas las fechas de canje
            $fechasCanjes = $venta->canjes->pluck('fechaHora_Canje')->toArray();
        
            // Obtener la fecha más reciente de todas las fechas de canje
            $fechaMasReciente = !empty($fechasCanjes) ? max($fechasCanjes) : 'Sin fecha';
        
            // Obtener 'puntosRestantes_Canje' del último canje y si no existe asignar puntos ganados
            $puntosRestantes = $venta->canjes->pluck('puntosRestantes_Canje');

            if (!$puntosRestantes) {
                $puntosRestantes = $venta->puntosGanados_VentaIntermediada;
            }
        
            // Crear un objeto para retorno
            $ventaObj = new \stdClass();
            $ventaObj->idVentaIntermediada = $idLimpio;
            $ventaObj->tipoComprobante = $tipoComprobante;
            $ventaObj->idTecnico = $venta->idTecnico;
            $ventaObj->nombreTecnico = $venta->nombreTecnico;
            $ventaObj->tipoCodigoCliente_VentaIntermediada = $venta->tipoCodigoCliente_VentaIntermediada;
            $ventaObj->codigoCliente_VentaIntermediada = $venta->codigoCliente_VentaIntermediada;
            $ventaObj->nombreCliente_VentaIntermediada = $venta->nombreCliente_VentaIntermediada;
            $ventaObj->fechaHoraEmision_VentaIntermediada = $venta->fechaHoraEmision_VentaIntermediada;
            $ventaObj->fechaHoraCargada_VentaIntermediada = $venta->fechaHoraCargada_VentaIntermediada;
            $ventaObj->montoTotal_VentaIntermediada = $venta->montoTotal_VentaIntermediada;
            $ventaObj->puntosGanados_VentaIntermediada = $venta->puntosGanados_VentaIntermediada;
            $ventaObj->estadoVentaIntermediada = $venta->estadoVentaIntermediada;
            $ventaObj->puntosRestantes = $puntosRestantes;
            $ventaObj->fechaHoraCanje = $fechaMasReciente;
        
            return $ventaObj;
        });
        
        $tecnicos = Tecnico::all();
        return view('dashboard.ventasIntermediadas', compact('ventas', 'tecnicos'));
    }*/

    /*public function create()
    {
        // Obtener todas las ventas intermediadas con sus canjes y estado de venta
        $ventasIntermediadas = VentaIntermediada::all(); 
        //dd($ventasIntermediadas);

        $ventas = $ventasIntermediadas->map(function ($venta) {
            // Limpiar id
            $idLimpio = $this->limpiarIDs($venta->idVentaIntermediada);

            // Obtener tipo de comprobante
            $tipoComprobante = $this->detectarTipoComprobante($venta->idVentaIntermediada);

            // Obtener el último canje basado en la fecha más reciente
            $ultimoCanje = $venta->canjes->sortByDesc('fechaHora_Canje')->first();

            // Si existe un canje, usa sus valores, si no, usa puntos ganados y asigna 'Sin fecha'
            $fechaMasReciente = $ultimoCanje ? $ultimoCanje->fechaHora_Canje : 'Sin fecha';
            $puntosRestantes = $ultimoCanje ? $ultimoCanje->puntosRestantes_Canje : $venta->puntosGanados_VentaIntermediada;

            // Calcular dos días transcurridos desde la fecha de emisión del comprobante hasta la fecha de hoy
            $diasTranscurridos = $this->returnDiasTranscurridosHastaHoy($venta->fechaHoraEmision_VentaIntermediada);
            
            // Crear un objeto para retorno
            $ventaObj = new \stdClass();
            $ventaObj->idVentaIntermediada = $idLimpio;
            $ventaObj->tipoComprobante = $tipoComprobante;
            $ventaObj->idTecnico = $venta->idTecnico;
            $ventaObj->nombreTecnico = $venta->nombreTecnico;
            $ventaObj->tipoCodigoCliente_VentaIntermediada = $venta->tipoCodigoCliente_VentaIntermediada;
            $ventaObj->codigoCliente_VentaIntermediada = $venta->codigoCliente_VentaIntermediada;
            $ventaObj->nombreCliente_VentaIntermediada = $venta->nombreCliente_VentaIntermediada;
            $ventaObj->fechaHoraEmision_VentaIntermediada = $venta->fechaHoraEmision_VentaIntermediada;
            $ventaObj->fechaHoraCargada_VentaIntermediada = $venta->fechaHoraCargada_VentaIntermediada;
            $ventaObj->montoTotal_VentaIntermediada = $venta->montoTotal_VentaIntermediada;
            $ventaObj->puntosGanados_VentaIntermediada = $venta->puntosGanados_VentaIntermediada;
            $ventaObj->idEstadoVenta = $venta->idEstadoVenta;
            $ventaObj->nombre_EstadoVenta = $venta->nombre_EstadoVenta;
            $ventaObj->puntosRestantes = $puntosRestantes;
            $ventaObj->diasTranscurridos = $diasTranscurridos;
            $ventaObj->fechaHoraCanje = $fechaMasReciente;

            return $ventaObj;
        });

        //dd($ventas);

        $tecnicos = Tecnico::all();
        return view('dashboard.ventasIntermediadas', compact('ventas', 'tecnicos'));
    }*/

    public function agregarCamposExtraVentas($ventas) {
        foreach ($ventas as $venta) {
            $venta->tipoComprobante = $this->detectarTipoComprobante($venta->idVentaIntermediada);
            $venta->diasTranscurridos = $this->returnDiasTranscurridosHastaHoy($venta->fechaHoraEmision_VentaIntermediada);
          
            $idEstado = $this->updateStateVentaIntermediada($venta->idVentaIntermediada, $venta->puntosActuales_VentaIntermediada);
            $nombreEstado = $this->getNombreEstadoVentaIntermediadaActualById($venta->idVentaIntermediada);

            $venta->idEstadoVenta = $idEstado; 
            $venta->nombre_EstadoVenta = $nombreEstado; 
        }
        return $ventas;
    }

    public static function updateStateVentaIntermediada($idVentaIntermediada, $nuevosPuntosActuales) {
        $venta = VentaIntermediada::findOrFail($idVentaIntermediada);
        $idEstado = VentaIntermediadaController::returnStateIdVentaIntermediada($idVentaIntermediada, $nuevosPuntosActuales);
        $venta->update([
            'puntosActuales_VentaIntermediada' => $nuevosPuntosActuales,
            'idEstadoVenta' => $idEstado,
        ]);

        return $idEstado;
    }

    public function create() {
        try {
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
                                
            $ventas = $this->agregarCamposExtraVentas($ventasIntermediadas);
           
            //dd($ventas->pluck('fechaVenta', 'idVentaIntermediada'));
            //dd($ventas->pluck('horaVenta', 'idVentaIntermediada'));
            //dd($ventas->pluck('fechaCargada', 'idVentaIntermediada'));
            //dd($ventas->pluck('horaCargada', 'idVentaIntermediada'));

            $tecnicoController = new TecnicoController();
            $tecnicos = $tecnicoController->returnModelsTecnicosWithOficios();
            $idsNombresOficios = $tecnicoController->returnArrayIdsNombresOficios(); 

            return view('dashboard.ventasIntermediadas', compact('ventas', 'tecnicos', 'idsNombresOficios'));
        } catch (\Exception $e) {
            dd("Error al mostrar las ventas intermediadas: " . $e->getMessage());
        }
    }
    
    function store(Request $request) 
    {
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

    public function delete(Request $request)
    {
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

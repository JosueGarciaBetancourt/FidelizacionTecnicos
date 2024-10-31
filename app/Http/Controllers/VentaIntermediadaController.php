<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use App\Models\EstadoVenta;
use Illuminate\Http\Request;
use App\Models\VentaIntermediada;
use App\Http\Controllers\TecnicoController;
use Illuminate\Support\Facades\DB;

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

    public function create() {
        // Obtener todas las ventas intermediadas con sus canjes y estado de venta
        $ventasIntermediadas = VentaIntermediada::query()
            ->leftJoin('Canjes', 'VentasIntermediadas.idVentaIntermediada', '=', 'Canjes.idVentaIntermediada')
            ->join('EstadoVentas', 'VentasIntermediadas.idEstadoVenta', '=', 'EstadoVentas.idEstadoVenta')
            ->select([
                'VentasIntermediadas.*',
                'Canjes.fechaHora_Canje as fechaHora_Canje',
                'Canjes.puntosRestantes_Canje as puntosRestantes_Canje',
                'EstadoVentas.nombre_EstadoVenta as nombre_EstadoVenta'
            ])
            ->get();
    
        // Agrupar por idVentaIntermediada y seleccionar el más reciente
        $ventasAgrupadas = $ventasIntermediadas->groupBy('idVentaIntermediada')->map(function ($grupo) {
            return $grupo->sortByDesc('fechaHora_Canje')->first(); // Tomar la venta con la fechaHora_Canje más reciente
        });
    
        // Mapear los resultados para añadir campos adicionales
        $ventas = $ventasAgrupadas->map(function ($venta) {
            // Limpiar id
            $idLimpio = $this->limpiarIDs($venta->idVentaIntermediada);
    
            // Obtener tipo de comprobante
            $tipoComprobante = $this->detectarTipoComprobante($venta->idVentaIntermediada);
    
            // Obtener el último canje basado en la fecha más reciente
            $ultimoCanje = $venta->fechaHora_Canje ? $venta->fechaHora_Canje : 'Sin fecha';
            $puntosRestantes = $venta->puntosRestantes_Canje ?? $venta->puntosGanados_VentaIntermediada;
    
            // Calcular días transcurridos desde la fecha de emisión del comprobante hasta hoy
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
            $ventaObj->fechaHoraCanje = $ultimoCanje;
    
            return $ventaObj;
        });
    
        // Cargar la vista con las ventas y los técnicos
        $tecnicoController = new TecnicoController();
        $tecnicos = $tecnicoController->returnModelsTecnicosWithOficios();
        $idsNombresOficios = $tecnicoController->returnAllIdsNombresOficios(); 

        return view('dashboard.ventasIntermediadas', compact('ventas', 'tecnicos', 'idsNombresOficios'));
    }
    
    function store(Request $request) 
    {
        $validatedData = $request->validate([
            'idVentaIntermediada' => 'required|string',
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

    public function getComprobantesEnEsperaByIdTecnico($idTecnico)
    {
        $comprobantes = VentaIntermediada::with('estadoVenta') // Cargar la relación con EstadoVentas
                                            ->where('idTecnico', $idTecnico)
                                            ->whereIn('idEstadoVenta', [1, 2])
                                            ->get();
        return response()->json($comprobantes);
    }
}

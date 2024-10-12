<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Psy\debug;
use App\Models\VentaIntermediada;
use App\Models\Canje;
use App\Models\Tecnico;
use Illuminate\Support\Facades\Log;

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

    public function create()
    {
        // Obtener todas las ventas intermediadas con sus canjes y cargar los modelos relacionados
        $ventasIntermediadas = VentaIntermediada::with('canjes')->get();

        Log::info('ventasIntermediadas: ' . $ventasIntermediadas);

        $ventas = $ventasIntermediadas->map(function ($venta) {

            // Limpiar id
            $idLimpio = $this->limpiarIDs($venta->idVentaIntermediada);

            // Obtener tipo de comprobante
            $tipoComprobante = $this->detectarTipoComprobante($venta->idVentaIntermediada);

            // Obtener todas las fechas de canje
            $fechasCanjes = $venta->canjes->pluck('fechaHora_Canje')->toArray();
        
            // Obtener la fecha más reciente de todas las fechas de canje
            $fechaMasReciente = !empty($fechasCanjes) ? max($fechasCanjes) : 'Sin fecha';
        
            // Sumar 'totalPuntosCanjeados_Canje' para cada venta intermediada
            $totalPuntosCanjeados = $venta->canjes->sum('totalPuntosCanjeados_Canje');
        
            // Calcular puntos restantes
            $puntosRestantes = $venta->puntosGanados_VentaIntermediada - $totalPuntosCanjeados;
        
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
            $ventaObj->totalPuntosCanjeados = $totalPuntosCanjeados;
            $ventaObj->puntosRestantes = $puntosRestantes;
            $ventaObj->fechaHoraCanje = $fechaMasReciente;
        
            return $ventaObj;
        });
        
        $tecnicos = Tecnico::all();
        return view('dashboard.ventasIntermediadas', compact('ventas', 'tecnicos'));
    }

    function store(Request $request) 
    {
        // Crear una venta intermediada con todos los campos de la request recepcionada
        VentaIntermediada::create($request->all());
        // Los datos que no se envian tienen valores por default en la migración   
        return redirect()->route('ventasIntermediadas.create')->with('successVentaIntermiadaStore', 'Venta Intermediada guardada correctamente');
    }

    public function getComprobantesEnEsperaByIdTecnico($idTecnico)
    {
        // Filtramos por 'idTecnico' y 'estadoVentaIntermediada'
        $comprobantes = VentaIntermediada::where('idTecnico', $idTecnico)
                                        ->where('estadoVentaIntermediada', 'En espera')
                                        ->get();

        return response()->json($comprobantes);
    }
}

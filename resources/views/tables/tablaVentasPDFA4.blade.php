@extends('layouts.layoutTablePDFA4')

@section('title', 'Listado de Ventas Intermediadas')

@push('styles')
    <style>
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .celda-montoTotal {
            width: 6rem;
        }

        .celda-puntos {
            width: 6rem;
        }

        .celda-tecnico {
            width: 6rem;
        }

        .estado__celda {
            padding: 0 !important;
            margin: 0 !important;
            text-align: center !important; 
        }

        .estado__celda span {
            padding: 5px;
            border-radius: 5px;
            margin: 0;
            font-weight: bold;
            font-size: 0.7rem;
            display: inline-block;
            width: 4rem;
        }

        /*ESTILOS DE LOS ESTADOS DE LAS VENTAS INTERMEDIADAS*/
        /* En espera y En espera (solicitado desde app)*/
        .estado__span-1, .estado__span-5 { 
            color: #2394da;
            background-color: rgba(35, 78, 218, 0.2) !important;
        }

        /* Redimido (parcial) */
        .estado__span-2 {
            color: #ff9800;
            background-color: rgba(255, 152, 0, 0.2) !important;
        }

        /* Redimido (completo) */
        .estado__span-3 {
            color: #4caf50;
            background-color: rgba(76, 175, 80, 0.2) !important;
        }   

        /* Tiempo agotado */
        .estado__span-4 {
            color: #f44336;
            background-color: rgba(244, 67, 54, 0.2) !important;
            width: max-content;
        }

        .estadoVentaTH {
            width: 5rem;
        }
    </style>
@endpush

@section('header')
    <table class="tblHeader">
        <tbody>
            <tr>
                <td>
                    <img id="logoDimacof" src="{{ public_path('images/logo_DIMACOF_recortado.png') }}" alt="logo_Dimacof.png">
                </td>
                <td>
                    <h1>Club de Técnicos</h1>
                </td>
            </tr>
        </tbody>
    </table>
@endsection

@section('content')
    <div class="tblTitle">Listado de Ventas Intermediadas</div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Número de venta</th>
                    <th>Fecha y Hora de Emisión</th>
                    <th>Fecha y Hora Cargada</th>
                    <th>Cliente</th>
                    <th>Monto Total</th>
                    <th class="celda-puntos">Puntos</th>
                    <th class="celda-tecnico">Técnico</th>
                    <th>Fecha y Hora Canjeada</th>
                    <th>Días hasta hoy</th>
                    <th class="estadoVentaTH">Estado</th> 
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $venta)
                <tr>
                    <td class="celda-centered">{{ $venta['index'] }}</td>
                    <td>{{ $venta['idVentaIntermediada'] }} <br>
                        <small> {{ $venta['tipoComprobante'] }}</small>
                    </td>
                    <td class="celda-centered">{{ $venta['fechaHoraEmision_VentaIntermediada'] }}</td>
                    <td class="celda-centered">{{ $venta['fechaHoraCargada_VentaIntermediada'] }}</td>
                    <td>{{ $venta['nombreCliente_VentaIntermediada'] }}<br>
                            <small>{{ $venta['tipoCodigoCliente_VentaIntermediada'] }}: {{ $venta['codigoCliente_VentaIntermediada'] }}</small>
                    </td>

                    <td class="celda-centered celda-montoTotal">{{ $venta['montoTotal_VentaIntermediada'] }}</td>
                    <td><small>P. Iniciales:</small> {{ $venta['puntosGanados_VentaIntermediada'] }} <br>
                                                <small>P. Actuales:</small> {{ $venta['puntosActuales_VentaIntermediada'] }}  
                    </td>
                    <td>{{ $venta['nombreTecnico'] }}<br>
                                                <small>DNI: {{ $venta['idTecnico'] }}</small>
                    </td>
                    <td class="celda-centered">{{ $venta['fechaHora_Canje'] }}</td>
                    <td class="celda-centered">{{ $venta['diasTranscurridos'] }}</td>
                    <td class="estado__celda">
                        <span class="estado__span-{{ $venta['idEstadoVenta'] }}">
                            {{ $venta['nombre_EstadoVenta'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
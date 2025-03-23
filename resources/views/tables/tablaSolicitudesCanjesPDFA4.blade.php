@extends('layouts.layoutTablePDFA4')

@section('title', 'Listado de Solicitudes de Canjes')

@push('styles')
    <style> 
        * {
            padding: 0 10px 0 3px;
            margin: 0;
        }

        .tblHeader {
            padding-top: 2.8rem;
        }

        .table-container td, th{
            font-size: 10.5px;
        }

        .celdas-fechaHora {
            width: 3.5rem;
        }
        
       .celdaVenta {
            width: 5rem;
        }
        
        .celda-tecnico {
            width: 76px;
        }

        .celda-comentario {
            width: 50px;
        }

        /*ESTILOS DE LOS ESTADOS DE LAS VENTAS INTERMEDIADAS*/
        .estado__celda {
            padding: 0 !important;
            margin: 0 !important;
            text-align: center !important; 
            width: 3.5rem;
        }

        .estado__celda span {
            padding: 5px;
            border-radius: 5px;
            margin: 0;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
            width: 2.5rem;
        }

        /*Pendiente */
        .estado__span-1 { 
            color: #6c757d;
            background-color: rgba(96, 98, 107, 0.2) !important;
        }

        /* Aprobado */
        .estado__span-2 {
            color: #4caf50;
            background-color: rgba(76, 175, 80, 0.2) !important;
        }

        /* Rechazado */
        .estado__span-3 {
            color: #f44336;
            background-color: rgba(244, 67, 54, 0.2) !important;
        }   

         /* Tiempo Agotado */
         .estado__span-4 {
            color: #ffffff; 
            background-color: #6a4d57 !important; 
        }   

        .celda-puntos {
            width: 5.5rem;
        }

        .celda-descripcion {
            width: 4rem;
        }

        .grupo-par {
            background-color: #f9f9f9;
        }

        .grupo-impar {
            background-color: #fff;
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
    <div class="tblTitle">Listado de Solicitudes de Canjes</div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Código</th>
                    <th rowspan="2">Estado</th>
                    <th rowspan="2" class="celdas-fechaHora">Fecha y Hora</th>
                    <th rowspan="2" class="celdaVenta">Venta Asociada</th>
                    <th rowspan="2" class="celdas-fechaHora">Fecha y Hora de emisión</th>
                    <th rowspan="2">Días hasta hoy</th>
                    <th rowspan="2" class="celda-tecnico">Técnico</th>
                    <th rowspan="2" class="celda-comentario">Comentario</th>
                    <th rowspan="2" class="celda-puntos">Puntos</th>
                    <th colspan="6">RECOMPENSAS</th>
                </tr>
                <tr>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th class="celda-descripcion">Descripción</th>
                    <th>Cantidad</th>
                    <th>Costo puntos</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $solicitudCanje)
                    @php
                        $recompensas = $solicitudCanje['recompensasJSON'];
                        $grupoClassname = $loop->index % 2 == 0 ? 'grupo-par' : 'grupo-impar';
                    @endphp
                    
                    @foreach ($recompensas as $index => $recompensa)
                        <tr class="{{ $grupoClassname }}">
                            @if ($index === 0)
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $solicitudCanje['index'] }}</td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $solicitudCanje['idSolicitudCanje'] }}</td>
                                <td class="estado__celda" rowspan="{{ count($recompensas) }}">
                                    <span class="estado__span-{{ $solicitudCanje['idEstadoSolicitudCanje'] }}">
                                        {{ $solicitudCanje['nombre_EstadoSolicitudCanje'] }}
                                    </span>
                                </td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $solicitudCanje['fechaHora_SolicitudCanje'] }}</td>
                                <td rowspan="{{ count($recompensas) }}">{{ $solicitudCanje['idVentaIntermediada'] }} <br>
                                    Puntos Generados: {{ $solicitudCanje['puntosComprobante_SolicitudCanje'] }}
                                </td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $solicitudCanje['fechaHoraEmision_VentaIntermediada'] }}</td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $solicitudCanje['diasTranscurridos_SolicitudCanje'] }}</td>
                                <td rowspan="{{ count($recompensas) }}">{{ $solicitudCanje['nombreTecnico'] }} <br>
                                    <small>DNI: {{ $solicitudCanje['idTecnico'] }}</small>
                                </td>
                                <td rowspan="{{ count($recompensas) }}">{{ $solicitudCanje['comentario_SolicitudCanje'] }}</td>
                                <td rowspan="{{ count($recompensas) }}">
                                    P. Actuales: {{ $solicitudCanje['puntosActuales_SolicitudCanje'] }}<br>
                                    P. Canjeados: {{ $solicitudCanje['puntosCanjeados_SolicitudCanje'] }}<br>
                                    P. Restantes: {{ $solicitudCanje['puntosRestantes_SolicitudCanje'] }}
                                </td>
                            @endif
                            <td class="celda-centered">{{ $recompensa['idRecompensa'] }}</td>
                            <td class="celda-centered">{{ $recompensa['nombre_TipoRecompensa'] }}</td>
                            <td>{{ $recompensa['descripcionRecompensa'] }}</td>
                            <td class="celda-centered">{{ $recompensa['cantidad'] }}</td>
                            <td class="celda-centered">{{ $recompensa['costoRecompensa'] }}</td>
                            <td class="celda-centered">{{ $recompensa['puntosTotales'] }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

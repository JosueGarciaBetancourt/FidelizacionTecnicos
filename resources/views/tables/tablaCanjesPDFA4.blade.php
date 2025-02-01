@extends('layouts.layoutTablePDFA4')

@section('title', 'Listado de Canjes')

@push('styles')
    <style> 
        * {
            padding: 0 10px 0 3px;
            margin: 0;
        }

        .tblHeader {
            padding-top: 2.8rem;
        }

        .celdas-fechaHora {
            width: 4rem;
        }
        
        .celda-tecnico {
            width: 5.5rem;
        }

        .celda-venta {
            width: 5.5rem;
        }
        
        .celda-puntos {
            width: 6.5rem;
        }

        .celda-descripcion {
            width: 4.5rem;
        }

        /* Aplicar color por grupo de canje */
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
    <div class="tblTitle">Listado de Canjes</div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Código</th>
                    <th rowspan="2" class="celdas-fechaHora">Fecha y Hora</th>
                    <th rowspan="2" class="celda-venta">Venta Asociada</th>
                    <th rowspan="2" class="celdas-fechaHora">Fecha y Hora de emisión</th>
                    <th rowspan="2">Días hasta hoy</th>
                    <th rowspan="2" class="celda-tecnico">Técnico</th>
                    <th rowspan="2">Puntos</th>
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
                @foreach ($data as $canje)
                    @php
                        $recompensas = $canje['recompensasJSON'];
                        $grupoClassname = $loop->index % 2 == 0 ? 'grupo-par' : 'grupo-impar';
                    @endphp
                    
                    @foreach ($recompensas as $index => $recompensa)
                        <tr class="{{ $grupoClassname }}">
                            @if ($index === 0)
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $canje['index'] }}</td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $canje['idCanje'] }}</td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $canje['fechaHora_Canje'] }}</td>
                                <td rowspan="{{ count($recompensas) }}">{{ $canje['idVentaIntermediada'] }}<br>
                                    <small>Puntos Generados: {{ $canje['puntosComprobante_Canje'] }}</small>
                                </td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $canje['fechaHoraEmision_VentaIntermediada'] }}</td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $canje['diasTranscurridos_Canje'] }}</td>
                                <td rowspan="{{ count($recompensas) }}">{{ $canje['nombreTecnico'] }}<br>
                                    <small>DNI: {{ $canje['idTecnico'] }}</small>
                                </td>
                                <td class="celda-puntos" rowspan="{{ count($recompensas) }}">
                                    <small>P. Actuales:</small>{{ $canje['puntosActuales_Canje'] }}<br>
                                    <small>P. Canjeados:</small>{{ $canje['puntosCanjeados_Canje'] }}<br>
                                    <small>P. Restantes:</small>{{ $canje['puntosRestantes_Canje'] }}
                                </td>
                            @endif
                            <td class="celda-centered">{{ $recompensa['idRecompensa'] }}</td>
                            <td class="celda-centered">{{ $recompensa['nombre_TipoRecompensa'] }}</td>
                            <td><small>{{ $recompensa['descripcionRecompensa'] }}</small></td>
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

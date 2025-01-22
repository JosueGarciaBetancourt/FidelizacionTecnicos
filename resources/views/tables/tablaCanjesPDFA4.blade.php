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

       .celdaVenta {
            width: 5.5rem;
        }
        
       .celdaTecnico {
            width: 5.3rem;
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
                    <th rowspan="2">Fecha y Hora</th>
                    <th rowspan="2" class="celdaVenta">Venta Asociada</th>
                    <th rowspan="2">Fecha y Hora de emisión</th>
                    <th rowspan="2">Días transcurridos</th>
                    <th rowspan="2" class="celdaTecnico">Técnico</th>
                    <th rowspan="2">Puntos canjeados</th>
                    <th rowspan="2">Puntos restantes</th>
                    <th colspan="6">RECOMPENSAS</th>
                </tr>
                <tr>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
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
                                <td rowspan="{{ count($recompensas) }}">{{ $canje['idVentaIntermediada'] }} <br>
                                    <small>Puntos Generados: {{ $canje['puntosComprobante_Canje'] }}</small>
                                </td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $canje['fechaHoraEmision_VentaIntermediada'] }}</td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $canje['diasTranscurridos_Canje'] }}</td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $canje['nombreTecnico'] }} <br>
                                    <small>DNI: {{ $canje['idTecnico'] }}</small>
                                </td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $canje['puntosCanjeados_Canje'] }}</td>
                                <td class="celda-centered" rowspan="{{ count($recompensas) }}">{{ $canje['puntosRestantes_Canje'] }}</td>
                            @endif
                            <td class="celda-centered">{{ $recompensa['idRecompensa'] }}</td>
                            <td class="celda-centered">{{ $recompensa['nombre_TipoRecompensa'] }}</td>
                            <td class="celda-centered">{{ $recompensa['descripcionRecompensa'] }}</td>
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

@extends('layouts.layoutTablePDFA4')

@section('title', 'Listado de Recompensas')

@push('styles')
    <style>
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .celda-numeral {
            width: 1rem;
        }

        .celda__tipoRecompensa {
            padding: 15px !important;
            margin: 0 !important;
            text-align: center !important; 
            width: 4rem;
        }

        .celda__tipoRecompensa span {
            padding: 5px;
            border-radius: 5px;
            margin: 0;
            font-weight: bold;
            font-size: 0.7rem;
            display: inline-block;
            width: 4rem;
        }

        .tipoRecompensa__span-1 {
            color: #139161;
            background-color: rgba(19, 145, 97, 0.15) !important;
        }

        .tipoRecompensa__span-2 {
            color: #9C27B0;
            background-color: rgba(156, 39, 176, 0.15) !important;
        }

        .tipoRecompensa__span-3 {
            color: #2196F3;
            background-color: rgba(33, 150, 243, 0.15) !important;
        }

        .tipoRecompensa__span-4 {
            color: #FF9800;
            background-color: rgba(255, 152, 0, 0.15) !important;
        }

        .idRecompensa {
            width: 5rem;
        }

        .celda-descripcion {
            width: 20rem;
        }

        .celda-costo {
            width: 4rem;
        }

        .celda-stock {
            width: 4rem;
        }

        .celdas-fechaHora {
            width: 7rem;
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
    <div class="tblTitle">Listado de Recompensas</div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="celda-numeral">#</th>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th class="celda-descripcion">Descripción</th>
                    <th class="celda-costo">Costo (puntos)</th>
                    <th class="celda-stock">Stock (unidades)</th>
                    <th class="celdas-fechaHora">Fecha y Hora de creación</th>
                    <th class="celdas-fechaHora">Fecha y Hora de actualización</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $recompensa)
                    <tr>
                        <td class="celda-centered">{{ $recompensa['index'] }} </td> 
                        <td class="celda-centered idRecompensa">{{ $recompensa['idRecompensa'] }}</td>
                        <td class="celda__tipoRecompensa">
                            <span class="tipoRecompensa__span-{{ $recompensa['idTipoRecompensa'] }}">
                                {{ $recompensa['nombre_TipoRecompensa'] }}
                            </span>
                        </td>
                        <td>{{ $recompensa['descripcionRecompensa']}} </td>
                        <td class="celda-centered">{{ $recompensa['costoPuntos_Recompensa'] }}</td>
                        <td class="celda-centered">{{ $recompensa['stock_Recompensa'] }}</td>
                        <td class="celda-centered">{{ $recompensa['created_at'] }}</td>
                        <td class="celda-centered">{{ $recompensa['updated_at'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

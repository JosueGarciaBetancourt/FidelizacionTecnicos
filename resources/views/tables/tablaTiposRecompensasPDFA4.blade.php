@extends('layouts.layoutTablePDFA4')

@section('title', 'Listado de Tipos de Recompensas')

@push('styles')
    <style>
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .codigoTipoRecompensa {
            width: 5rem;
        }

        .nombreTipoRecompensa {
            width: 12rem;
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
    <div class="tblTitle">Listado de Tipos de Recompensas</div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th class="codigoTipoRecompensa">Código</th>
                    <th class="nombreTipoRecompensa">Nombre</th>
                    <th>Descripción</th>
                    <th>Fecha y Hora de creación</th>
                    <th>Fecha y Hora de actualización</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $tipoRecompensa)
                    <tr>
                        <td class="celda-centered">{{ $tipoRecompensa['index'] }} </td> 
                        <td class="celda-centered">{{ $tipoRecompensa['codigoTipoRecompensa'] }}</td>
                        <td class="celda-centered">{{ $tipoRecompensa['nombre_TipoRecompensa'] }}</td>
                        <td>{{ $tipoRecompensa['descripcion_TipoRecompensa'] }}</td>
                        <td class="celda-centered">{{ $tipoRecompensa['created_at'] }}</td>
                        <td class="celda-centered">{{ $tipoRecompensa['updated_at'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

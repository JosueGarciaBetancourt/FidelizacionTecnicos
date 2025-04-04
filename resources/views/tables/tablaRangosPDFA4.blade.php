@extends('layouts.layoutTablePDFA4')

@section('title', 'Listado de Rangos')

@push('styles')
    <style>
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .codigoRango {
            width: 5rem;
        }

        .nombreRango {
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
    <div class="tblTitle">Listado de Rangos</div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th class="codigoRango">Código</th>
                    <th class="nombreRango">Nombre</th>
                    <th>Descripción</th>
                    <th>Puntos mínimos</th>
                    <th>Fecha y Hora de creación</th>
                    <th>Fecha y Hora de actualización</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $rango)
                    <tr>
                        <td class="celda-centered">{{ $rango['index'] }} </td> 
                        <td class="celda-centered">{{ $rango['codigoRango'] }}</td>
                        <td class="celda-centered">{{ $rango['nombre_Rango'] }}</td>
                        <td>{{ $rango['descripcion_Rango'] }}</td>
                        <td class="celda-centered">{{ $rango['puntosMinimos_Rango'] }}</td>
                        <td class="celda-centered">{{ $rango['created_at'] }}</td>
                        <td class="celda-centered">{{ $rango['updated_at'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@extends('layouts.layoutTablePDFA4')

@section('title', 'Listado de Rangos')

@push('styles')
    <style>
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .nombreOficio {
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
    <div class="tblTitle">Listado de Oficios</div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Código</th>
                    <th class="nombreOficio">Nombre</th>
                    <th>Descripción</th>
                    <th>Fecha y Hora de creación</th>
                    <th>Fecha y Hora de actualización</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $oficio)
                    <tr>
                        <td class="celda-centered">{{ $oficio['index'] }} </td> 
                        <td class="celda-centered">{{ $oficio['codigoOficio'] }}</td>
                        <td class="celda-centered">{{ $oficio['nombre_Oficio'] }}</td>
                        <td>{{ $oficio['descripcion_Oficio'] }}</td>
                        <td class="celda-centered">{{ $oficio['created_at'] }}</td>
                        <td class="celda-centered">{{ $oficio['updated_at'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

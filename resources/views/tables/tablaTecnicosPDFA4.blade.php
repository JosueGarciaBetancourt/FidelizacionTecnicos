@extends('layouts.layoutTablePDFA4')

@section('title', 'Listado de Técnicos')

@push('styles')
    <style>
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .rango {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            color: white;
            display: inline-block;
            min-width: 60px;
            text-align: center;
        }

        .rango.1 { /*sin rango*/ 
            color: #ffffff; 
            background-color: #6a4d57 !important; 
        }
        
        .rango.2 {
            background-color: #C0C0C0;
            color: #333333;
        }

        .rango.3 {
            background-color: #FFD700;
            color: #333333;
        }

        .rango.4 {
            background-color: #333333;
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
    <div class="tblTitle">Listado de Técnicos</div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Oficio</th>
                    <th>Celular</th>
                    <th>Fecha de nacimiento</th>
                    <th>Puntos actuales</th>
                    <th>Histórico de puntos</th>
                    <th>Rango</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $tecnico)
                <tr>
                    <td class="celda-centered">{{ $tecnico['index'] }}</td>
                    <td>{{ $tecnico['idTecnico'] }}</td>
                    <td>{{ $tecnico['nombreTecnico'] }}</td>
                    <td>{{ $tecnico['oficioTecnico'] }}</td>
                    <td>{{ $tecnico['celularTecnico'] }}</td>
                    <td class="celda-centered">{{ $tecnico['fechaNacimiento_Tecnico'] }}</td>
                    <td class="celda-centered">{{ $tecnico['totalPuntosActuales_Tecnico'] }}</td>
                    <td class="celda-centered">{{ $tecnico['historicoPuntos_Tecnico'] }}</td>
                    <td>
                        <span class="rango {{ $tecnico['idRango'] }}">
                            {{ $tecnico['rangoTecnico'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

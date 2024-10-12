@extends('layouts.layoutDashboard')

@section('title', 'Ventas Intermediadas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ventasIntermediadasStyling.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalAgregarVenta.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalAgregarNuevoTecnico.css') }}">
@endpush

@section('main-content')
    <div class="ventasIntermediadasContainer">
        <div class="firstRow">
            <x-btn-create-item onclick="openModal('modalAgregarVenta')"> 
                Agregar nueva venta 
            </x-btn-create-item>

            <!--Modal para agregar nueva venta-->
            @include('modals.ventasIntermediadas.modalAgregarVenta')

            <!--Modal para agregar nuevo técnico-->
            @include('modals.tecnicos.modalAgregarNuevoTecnico')
        </div>

        <x-modalSuccessAction 
            :idSuccesModal="'successModalVentaIntermediadaGuardada'"
            :message="'Venta Intermediada registrada correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalTecnicoGuardado'"
            :message="'Técnico guardado correctamente'"
        />


        <!--Tabla de ventas intermediadas-->
        <div class="thirdRow">
            <table id="tblVentasIntermediadas">
                <thead>
                    <tr>
                        <th class="celda-centered">#</th>
                        <th>Número de comprobante</th>
                        <th class="celda-centered">Fecha y Hora de Emisión</th>
                        <th class="celda-centered">Fecha y Hora Cargada</th>
                        <th>Cliente</th>
                        <th class="celda-centered">Monto Total</th>
                        <th class="celda-centered">Puntos Generados</th>
                        <th>Técnico</th>
                        <th class="celda-centered">Fecha y Hora Redimida</th>
                        <th class="celda-centered">Puntos restantes</th>
                        <th class="celda-centered">Estado</th> 
                    </tr>
                </thead>
                <tbody>
                    @php
                        $contador = 1;
                    @endphp
                    @foreach ($ventas as $venta)
                    <tr>
                        <td class="celda-centered">{{ $contador++ }}</td> 
                        <td>{{ $venta->idVentaIntermediada }} <br>
                            <small>{{ $venta->tipoComprobante}}</small>
                        </td>
                        <td class="celda-centered">{{ $venta->fechaHoraEmision_VentaIntermediada }}</td>
                        <td class="celda-centered">{{ $venta->fechaHoraCargada_VentaIntermediada }}</td>
                        <td>{{ $venta->nombreCliente_VentaIntermediada }} <br>
                            <small>{{ $venta->tipoCodigoCliente_VentaIntermediada }}:  
                                   {{ $venta->codigoCliente_VentaIntermediada }}
                            </small>
                        </td>
                        <td class="celda-centered">{{ $venta->montoTotal_VentaIntermediada }}</td>
                        <td class="celda-centered">{{ $venta->puntosGanados_VentaIntermediada }}</td>
                        <td>{{ $venta->nombreTecnico }} <br>
                            <small>DNI: {{ $venta->idTecnico }}</small>
                        </td>
                        <td class="celda-centered">{{ $venta->fechaHoraCanje }}</td>
                        <td class="celda-centered">{{ $venta->puntosRestantes}}</td>
                        <td class="estado__celda">
                            <span class="estado__span-{{strtolower(str_replace(' ', '-', $venta->estadoVentaIntermediada))}}">
                                {{ $venta->estadoVentaIntermediada }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/modalAgregarVentaScript.js') }}"></script>
    <script src="{{ asset('js/modalAgregarNuevoTecnico.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('successVentaIntermiadaStore'))
                    openModal('successModalVentaIntermediadaGuardada');
            @endif
            @if(session('successTecnicoStore'))
                    openModal('successModalTecnicoGuardado');
            @endif
        });
    </script>
@endpush
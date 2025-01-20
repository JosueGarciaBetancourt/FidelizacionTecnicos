@extends('layouts.layoutDashboard')

@section('title', 'Ventas Intermediadas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ventasIntermediadasStyling.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalAgregarVenta.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalAgregarNuevoTecnico.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEliminarVentaIntermediada.css') }}">
@endpush

@section('main-content')
    <div class="ventasIntermediadasContainer">
        <div class="firstRow">
            <x-btn-create-item onclick="openModal('modalAgregarVenta')"> Agregar nueva venta </x-btn-create-item>
            <x-btn-delete-item onclick="openModal('modalEliminarVentaIntermediada')"> Eliminar </x-btn-delete-item>
        </div>

        <div class="thirdRow">
            <table id="tblVentasIntermediadas">
                <thead>
                    <tr>
                        <th class="celda-centered">#</th>
                        <th>Número de venta</th>
                        <th class="celda-centered">Fecha y Hora de Emisión</th>
                        <th class="celda-centered">Fecha y Hora Cargada</th>
                        <th>Cliente</th>
                        <th class="celda-centered">Monto Total</th>
                        <th class="celda-centered">Puntos Generados</th>
                        <th>Técnico</th>
                        <th class="celda-centered">Fecha y Hora Canjeada</th>
                        <th class="celda-centered">Días hasta hoy</th>
                        <th class="celda-centered">Estado</th> 
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        
        @include('modals.ventasIntermediadas.modalEliminarVentaIntermediada')
        @include('modals.ventasIntermediadas.modalAgregarVenta')
        @include('modals.tecnicos.modalAgregarNuevoTecnico')

        <x-modalSuccessAction 
            :idSuccesModal="'successModalVentaIntermediadaGuardada'"
            :message="'Venta Intermediada registrada correctamente'"
        />
        
        <x-modalSuccessAction 
            :idSuccesModal="'successModalVentaIntermediadaEliminada'"
            :message="'Venta Intermediada eliminada correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalTecnicoGuardado'"
            :message="'Técnico guardado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalTecnicoRecontratado'"
            :message="'Técnico recontratado correctamente'"
        />

        <x-modalFailedAction 
            :idErrorModal="'errorModalVentaIntermediada'"
            :message="$errors->first('errorClaveForanea') ?? 'La venta intermediada no pudo ser eliminada.'"
        />
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/modalAgregarVentaScript.js') }}"></script>
    <script src="{{ asset('js/modalEliminarVentaIntermediada.js') }}"></script>
    <script src="{{ asset('js/modalAgregarNuevoTecnico.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('successVentaIntermiadaStore'))
                    openModal('successModalVentaIntermediadaGuardada');
            @endif
            @if(session('successVentaIntermiadaDelete'))
                    openModal('successModalVentaIntermediadaEliminada');
            @endif
            @if(session('successTecnicoStore'))
                    openModal('successModalTecnicoGuardado');
            @endif
            @if(session('successTecnicoRecontratadoStore'))
                    openModal('successModalTecnicoRecontratado');
            @endif
            @if ($errors->has('errorClaveForanea'))
                justOpenModal('errorModalVentaIntermediada');
            @endif
        });
    </script>
@endpush
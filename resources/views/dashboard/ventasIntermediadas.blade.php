@extends('layouts.layoutDashboard')

@section('title', 'Ventas Intermediadas')

@push('styles')
    {{-- 
    <link rel="stylesheet" href="{{ asset('css/ventasIntermediadasStyling.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalAgregarVenta.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalAgregarNuevoTecnico.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEditarMaxdayscanje.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEliminarVentaIntermediada.css') }}">
    <link rel="stylesheet" href="{{ asset('css/multiselectDropdown.css') }}">
    --}}
    @vite(['resources/css/ventasIntermediadasStyling.css'])
    @vite(['resources/css/modalAgregarVenta.css'])
    @vite(['resources/css/modalAgregarNuevoTecnico.css'])
    @vite(['resources/css/modalEditarMaxdayscanje.css'])
    @vite(['resources/css/modalEliminarVentaIntermediada.css'])
    @vite(['resources/css/multiselectDropdown.css'])
@endpush

@section('main-content')
    @php
        $isAsisstantLogged = Auth::check() && Auth::user()->idPerfilUsuario == 3;
    @endphp

    <div class="ventasIntermediadasContainer">
        @if (!$isAsisstantLogged)
            <div class="firstRow">
                <x-btn-create-item onclick="openModal('modalAgregarVenta')"> Agregar nueva venta </x-btn-create-item>
                @include('modals.ventasIntermediadas.modalAgregarVenta')
                @include('modals.tecnicos.modalAgregarNuevoTecnico')
                
                <x-btn-edit-item onclick="openModal('modalEditarMaxdayscanje')"> Editar días máximos de registro de venta </x-btn-edit-item>
                @include('modals.ventasIntermediadas.modalEditarMaxdayscanje')

                <x-btn-delete-item onclick="openModal('modalEliminarVentaIntermediada')"> Eliminar </x-btn-delete-item>
                @include('modals.ventasIntermediadas.modalEliminarVentaIntermediada')
            </div>
        @endif

        <div class="thirdRow">
            <table id="tblVentasIntermediadas">
                <thead>
                    <tr>
                        <th class="celda-centered">#</th>
                        <th>Número de venta</th>
                        <th class="celda-centered">Fecha y Hora de Emisión</th>
                        <th class="celda-centered">Fecha y Hora Cargada</th>
                        <th>Cliente</th>
                        <th class="celda-centered" id="celda-montoTotal">Monto Total</th>
                        <th class="celda-centered" id="celda-puntos">Puntos</th>
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
        
        <x-modalConfirmAction
            :idConfirmModal="'modalConfirmActionVentaIntermediada'"
            :message="'¿Está seguro de esta acción?'"
        />
        
        <x-modalSuccessAction 
            :idSuccesModal="'successModalVentaIntermediadaGuardada'"
            :message="'Venta Intermediada registrada correctamente'"
        />
        
        <x-modalSuccessAction 
            :idSuccesModal="'successModalTecnicoGuardado'"
            :message="'Técnico guardado correctamente'"
        />

        
        <x-modalSuccessAction 
            :idSuccesModal="'successModalMaxdayscanjeGuardado'"
            :message="'Días máximos de canje guardados correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalVentaIntermediadaEliminada'"
            :message="'Venta Intermediada eliminada correctamente'"
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
    <script src="{{ asset('js/multiSelectDropdown.js') }}"></script>
    <script src="{{ asset('js/modalAgregarNuevoTecnico.js') }}"></script>
    <script src="{{ asset('js/modalAgregarVentaScript.js') }}"></script>
    <script src="{{ asset('js/modalEditarMaxdayscanje.js') }}"></script>
    <script src="{{ asset('js/modalEliminarVentaIntermediada.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('successVentaIntermiadaStore'))
                    openModal('successModalVentaIntermediadaGuardada');
            @endif
            @if(session('successTecnicoStore'))
                    openModal('successModalTecnicoGuardado');
            @endif
            @if(session('successMaxdayscanjeStore'))
                    openModal('successModalMaxdayscanjeGuardado');
            @endif
            @if(session('successVentaIntermiadaDelete'))
                    openModal('successModalVentaIntermediadaEliminada');
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
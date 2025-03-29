@extends('layouts.layoutDashboard')

@section('title', 'Rangos')

@push('styles')
    {{-- 
    <link rel="stylesheet" href="{{ asset('css/rangosStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalRegistrarNuevoRango.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEditarRango.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalInhabilitarRango.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalRestaurarRango.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEliminarRango.css') }}"> 
    --}}
    @vite(['resources/css/rangosStyle.css'])
    @vite(['resources/css/modalRegistrarNuevoRango.css'])
    @vite(['resources/css/modalEditarRango.css'])
    @vite(['resources/css/modalInhabilitarRango.css'])
    @vite(['resources/css/modalRestaurarRango.css'])
    @vite(['resources/css/modalEliminarRango.css'])
@endpush

@section('main-content')
    @php
        $isAsisstantLogged = Auth::check() && Auth::user()->idPerfilUsuario == 3;
    @endphp

    <div class="rangosContainer">
        @if (!$isAsisstantLogged)
            <div class="firstRow">
                <x-btn-create-item onclick="openModal('modalRegistrarNuevoRango')"> Registrar nuevo rango
                </x-btn-create-item>
                @include('modals.rangos.modalRegistrarNuevoRango')

                <x-btn-edit-item onclick="openModal('modalEditarRango')"> Editar </x-btn-edit-item>
                @include('modals.rangos.modalEditarRango')

                <x-btn-disable-item onclick="openModal('modalInhabilitarRango')"> Inhabilitar </x-btn-disable-item>
                @include('modals.rangos.modalInhabilitarRango')

                <x-btn-recover-item onclick="openModal('modalRestaurarRango')"> Habilitar </x-btn-recover-item>
                @include('modals.rangos.modalRestaurarRango')

                <x-btn-delete-item onclick="openModal('modalEliminarRango')"> Eliminar </x-btn-delete-item>
                @include('modals.rangos.modalEliminarRango')
            </div>
        @endif

        <div class="secondRow">
            <table id="tblRangos">
                <thead>
                    <tr>
                        <th class="celda-centered">#</th>
                        <th class="celda-centered">Código</th>
                        <th class="celda-centered">Nombre</th>
                        <th>Descripción</th>
                        <th class="celda-centered">Puntos mínimos</th>
                        <th class="celda-centered">Fecha y Hora de creación</th>
                        <th class="celda-centered">Fecha y Hora de actualización</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $contador = 1;
                    @endphp
                    @foreach ($rangos as $rango)
                        <tr>
                            <td class="celda-centered">{{ $contador++ }}</td>
                            <td class="celda-centered">{{ $rango->codigoRango }}</td>
                            <td class="celda-centered celdaRango"> 
                                <span style="color: {{ $rango->colorTexto_Rango }}; background-color: {{ $rango->colorFondo_Rango }};">
                                    {{ $rango->nombre_Rango }}
                                </span> 
                            </td>
                            <td>{{ $rango->descripcion_Rango }}</td>
                            <td class="celda-centered">{{ $rango->puntosMinimos_Rango }}</td>
                            <td class="celda-centered">{{ $rango->created_at }}</td>
                            <td class="celda-centered">{{ $rango->updated_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-modalSuccessAction :idSuccesModal="'successModalRangoGuardado'" :message="'Rango guardado correctamente'" />

        <x-modalSuccessAction :idSuccesModal="'successModalRangoActualizado'" :message="'Rango actualizado correctamente'" />

        <x-modalSuccessAction :idSuccesModal="'successModalRangoDisable'" :message="'Rango inhabilitado correctamente'" />

        <x-modalSuccessAction :idSuccesModal="'successModalRangoRestaurado'" :message="'Rango habilitado correctamente'" />

        <x-modalSuccessAction :idSuccesModal="'successModalRangoDelete'" :message="'Rango eliminado correctamente'" />

        <x-modalFailedAction :idErrorModal="'errorModalRangoDelete'" :message="'El rango no puede ser eliminado porque hay técnicos asociados a este'" />
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/modalRegistrarNuevoRango.js') }}"></script>
    <script src="{{ asset('js/modalEditarRango.js') }}"></script>
    <script src="{{ asset('js/modalInhabilitarRango.js') }}"></script>
    <script src="{{ asset('js/modalRestaurarRango.js') }}"></script>
    <script src="{{ asset('js/modalEliminarRango.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('successRangoStore'))
                openModal('successModalRangoGuardado');
            @endif
            @if (session('successRangoUpdate'))
                openModal('successModalRangoActualizado');
            @endif
            @if (session('successRangoDisable'))
                openModal('successModalRangoDisable');
            @endif
            @if (session('successRangoRestaurado'))
                openModal('successModalRangoRestaurado');
            @endif
            @if (session('successRangoDelete'))
                openModal('successModalRangoDelete');
            @endif
            @if (session('errorRangoDelete'))
                justOpenModal('errorModalRangoDelete');
            @endif
        });
    </script>
@endpush

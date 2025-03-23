@extends('layouts.layoutDashboard')

@section('title', 'Rangos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/rangosStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalRegistrarNuevoRango.css') }}">
@endpush

@section('main-content')
    @php
        $isAsisstantLogged = Auth::check() && Auth::user()->idPerfilUsuario == 3;
    @endphp

    <div class="rangosContainer">
        @if (!$isAsisstantLogged)
            <div class="firstRow">
                <x-btn-create-item onclick="openModal('modalRegistrarNuevoRango')"> Registrar nuevo rango </x-btn-create-item>
                @include('modals.rangos.modalRegistrarNuevoRango')

                <x-btn-edit-item onclick="openModal('modalEditarRango')"> Editar </x-btn-edit-item>
                @include('modals.rangos.modalEditarRango')

                <x-btn-delete-item onclick="openModal('modalEliminarRango')"> Inhabilitar </x-btn-delete-item>
                {{--  @include('modals.rangos.modalEliminarRango') --}}

                <x-btn-recover-item onclick="openModal('modalRestaurarRango')"> Habilitar </x-btn-delete-item>
                {{-- @include('modals.rangos.modalRestaurarRango') --}}
            </div>
        @endif
        
        <div class="secondRow">
            <table id="tblRangos">
                <thead>
                    <tr>
                        <th class="celda-centered">#</th>
                        <th class="celda-centered">Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Puntos mínimos</th>
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
                            <td>{{ $rango->nombre_Rango }}</td>
                            <td>{{ $rango->descripcion_Rango }}</td>
                            <td>{{ $rango->puntosMinimos_Rango }}</td>
                            <td class="celda-centered">{{ $rango->created_at }}</td>
                            <td class="celda-centered">{{ $rango->updated_at }}</td>
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <x-modalSuccessAction 
            :idSuccesModal="'successModalRangoGuardado'"
            :message="'Rango guardado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalRangoActualizado'"
            :message="'Rango actualizado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalRangoEliminado'"
            :message="'Rango inhabilitado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalRangoRestaurado'"
            :message="'Rango habilitado correctamente'"
        />
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/modalRegistrarNuevoRango.js') }}"> </script>
    <script src="{{ asset('js/modalEditarRango.js') }}"> </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('successRangoStore'))
                openModal('successModalRangoGuardado');
            @endif
            @if(session('successRangoUpdate'))
                openModal('successModalRangoActualizado');
            @endif
            @if(session('successRangoDisable'))
                openModal('successModalRangoDisable');
            @endif
            @if(session('successRangoRestaurado'))
                openModal('successModalRangoRestaurado');
            @endif
            @if(session('successRangoDelete'))
                openModal('successModalRangoDelete');
            @endif
            @if(session('errorRangoDelete'))
                justOpenModal('errorModalRangoDelete');
            @endif
        });
    </script>
@endpush
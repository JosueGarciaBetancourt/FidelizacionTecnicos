@extends('layouts.layoutDashboard')

@section('title', 'Oficios')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/oficiosStyle.css') }}">
    <link rel="stylesheet" href="{{asset('css/modalRegistrarNuevoOficio.css')}}">
    <link rel="stylesheet" href="{{asset('css/modalEditarOficio.css')}}">
    <link rel="stylesheet" href="{{asset('css/modalInhabilitarOficio.css')}}">
    <link rel="stylesheet" href="{{asset('css/modalRestaurarOficio.css')}}">
    <link rel="stylesheet" href="{{asset('css/modalEliminarOficio.css')}}">
@endpush

@section('main-content')
    @php
        $isAsisstantLogged = Auth::check() && Auth::user()->idPerfilUsuario == 3;
    @endphp

    <div class="oficiosContainer">
        @if (!$isAsisstantLogged)
            <div class="firstRow">
                <x-btn-create-item onclick="openModal('modalRegistrarNuevoOficio')"> Registrar nuevo oficio </x-btn-create-item>
                @include('modals.oficios.modalRegistrarNuevoOficio')

                <x-btn-edit-item onclick="openModal('modalEditarOficio')"> Editar </x-btn-edit-item>
                @include('modals.oficios.modalEditarOficio')

                <x-btn-disable-item onclick="openModal('modalInhabilitarOficio')"> Inhabilitar </x-btn-disable-item>
                @include('modals.oficios.modalInhabilitarOficio')

                <x-btn-recover-item onclick="openModal('modalRestaurarOficio')"> Habilitar </x-btn-delete-item>
                @include('modals.oficios.modalRestaurarOficio')

                <x-btn-delete-item onclick="openModal('modalEliminarOficio')"> Eliminar </x-btn-delete-item>
                @include('modals.oficios.modalEliminarOficio')
            </div>
        @endif
        
        <div class="secondRow">
            <table id="tblOficios">
                <thead>
                    <tr>
                        <th class="celda-centered">#</th>
                        <th class="celda-centered">Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th class="celda-centered">Fecha y Hora de creación</th>
                        <th class="celda-centered">Fecha y Hora de actualización</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $contador = 1;
                    @endphp
                    @foreach ($oficios as $oficio)
                    <tr>
                        <td class="celda-centered">{{ $contador++ }}</td> 
                        <td class="celda-centered">{{ $oficio->codigoOficio }}</td>
                        <td>{{ $oficio->nombre_Oficio }}</td>
                        <td>{{ $oficio->descripcion_Oficio }}</td>
                        <td class="celda-centered">{{ $oficio->created_at }}</td>
                        <td class="celda-centered">{{ $oficio->updated_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <x-modalSuccessAction 
            :idSuccesModal="'successModalOficioGuardado'"
            :message="'Oficio guardado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalOficioActualizado'"
            :message="'Oficio actualizado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalOficioDisable'"
            :message="'Oficio inhabilitado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successOficioRestore'"
            :message="'Oficio habilitado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalOficioDelete'"
            :message="'Oficio eliminado correctamente'"
        />

        <x-modalFailedAction 
            :idErrorModal="'errorModalOficioDelete'"
            :message="'El oficio no puede ser eliminado porque hay técnicos asociados a este'"
        />
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/modalRegistrarNuevoOficio.js') }}"></script>
    <script src="{{ asset('js/modalEditarOficio.js') }}"></script>
    <script src="{{ asset('js/modalInhabilitarOficio.js') }}"></script>
    <script src="{{ asset('js/modalRestaurarOficio.js') }}"></script>
    <script src="{{ asset('js/modalEliminarOficio.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('successOficioStore'))
                openModal('successModalOficioGuardado');
            @endif
            @if(session('successOficioUpdate'))
                openModal('successModalOficioActualizado');
            @endif
            @if(session('successOficioDisable'))
                openModal('successModalOficioDisable');
            @endif
            @if(session('successOficioRestore'))
                openModal('successModalOficioRestaurado');
            @endif
            @if(session('successOficioDelete'))
                openModal('successModalOficioDelete');
            @endif
            @if(session('errorOficioDelete'))
                justOpenModal('errorModalOficioDelete');
            @endif
        });
    </script>
@endpush
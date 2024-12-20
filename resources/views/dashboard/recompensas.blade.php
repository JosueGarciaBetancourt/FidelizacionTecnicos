@extends('layouts.layoutDashboard')

@section('title', 'Recompensas')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/recompensasStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalRegistrarNuevaRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEditarRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEliminarRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalRestaurarRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalRegistrarNuevoTipoRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEditarTipoRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEliminarTipoRecompensa.css') }}">
@endpush

@section('main-content')
    <div class="recompensasContainer">
        <h3>Recompensa</h3>
        <div class="firstRowRecompensas">
            <x-btn-create-item onclick="openModal('modalRegistrarNuevaRecompensa')"> 
                Nueva recompensa
            </x-btn-create-item>
            @include('modals.recompensas.modalRegistrarNuevaRecompensa')

            <x-btn-edit-item onclick="openModal('modalEditarRecompensa')"> Editar </x-btn-edit-item>
            @include('modals.recompensas.modalEditarRecompensa')

            <x-btn-delete-item onclick="openModal('modalEliminarRecompensa')"> Inhabilitar </x-btn-delete-item>
            @include('modals.recompensas.modalEliminarRecompensa')

            <x-btn-recover-item onclick="openModal('modalRestaurarRecompensa')"> Habilitar </x-btn-delete-item>
            @include('modals.recompensas.modalRestaurarRecompensa')
        </div>

        <h3>Tipo de recompensa</h3>
        <div class="secondRowRecompensas">
            <x-btn-create-item onclick="openModal('modalRegistrarNuevoTipoRecompensa')"> 
                Nuevo tipo de recompensa
            </x-btn-create-item>
            @include('modals.recompensas.modalRegistrarNuevoTipoRecompensa')

            <x-btn-edit-item onclick="openModal('modalEditarTipoRecompensa')"> Editar </x-btn-edit-item>
            @include('modals.recompensas.modalEditarTipoRecompensa')

            <x-btn-delete-item onclick="openModal('modalEliminarTipoRecompensa')"> Eliminar </x-btn-delete-item>
            @include('modals.recompensas.modalEliminarTipoRecompensa')
        </div>
        
        <x-modalSuccessAction 
            :idSuccesModal="'successModalRecompensaGuardada'"
            :message="'Recompensa guardada correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalRecompensaActualizada'"
            :message="'Recompensa actualizada correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalRecompensaEliminada'"
            :message="'Recompensa inhabilitada correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalRecompensaRestaurada'"
            :message="'Recompensa habilitada correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalTipoRecompensaGuardado'"
            :message="'Tipo de recompensa guardado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalTipoRecompensaActualizado'"
            :message="'Tipo de recompensa actualizado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalTipoRecompensaEliminado'"
            :message="'Tipo de recompensa eliminado correctamente'"
        />

        <div class="thirdRow">
            <table id="tblRecompensas">
                <thead>
                    <tr>
                        <th class="celda-centered">#</th>
                        <th>Código</th>
                        <th class="celda-centered">Tipo</th>
                        <th id="idCeldaDescripcionRecompensa">Descripción</th>
                        <th class="celda-centered short">Costo (puntos)</th>
                        <th class="celda-centered short">Stock (unidades)</th>
                        <th class="celda-centered date">Fecha y Hora de creación</th>
                        <th class="celda-centered date">Fecha y Hora de actualización</th> 
                    </tr>
                </thead>
                <tbody>
                    @php
                        $contador = 1;
                    @endphp
                    @foreach ($recompensas as $recompensa)
                    <tr>
                        <td class="celda-centered">{{ $contador++ }}</td> 
                        <td>{{ $recompensa->idRecompensa }}</td>
                        <td class="celda__tipoRecompensa">
                            <span class="tipoRecompensa__span-{{strtolower(str_replace(' ', '-', $recompensa->idTipoRecompensa))}}">
                                {{ $recompensa->nombre_TipoRecompensa }}
                            </span>
                        </td>
                        <td>{{ $recompensa->descripcionRecompensa }}</td>
                        <td class="celda-centered">{{ $recompensa->costoPuntos_Recompensa }}</td>
                        <td class="celda-centered">{{ $recompensa->stock_Recompensa }}</td>
                        <td class="celda-centered">{{ $recompensa->created_at}}</td>
                        <td class="celda-centered">{{ $recompensa->updated_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/modalRegistrarNuevaRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalEditarRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalEliminarRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalRestaurarRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalRegistrarNuevoTipoRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalEditarTipoRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalEliminarTipoRecompensa.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('successRecompensaStore'))
                openModal('successModalRecompensaGuardada');
            @endif
            @if(session('successRecompensaUpdate'))
                openModal('successModalRecompensaActualizada');
            @endif
            @if(session('successRecompensaDelete'))
                openModal('successModalRecompensaEliminada');
            @endif
            @if(session('successRecompensaRestaurada'))
                openModal('successModalRecompensaRestaurada');
            @endif
            @if(session('successTipoRecompensaStore'))
                openModal('successModalTipoRecompensaGuardado');
            @endif
            @if(session('successTipoRecompensaUpdate'))
                openModal('successModalTipoRecompensaActualizado');
            @endif
            @if(session('successTipoRecompensaDelete'))
                openModal('successModalTipoRecompensaEliminado');
            @endif
        });
    </script>
@endpush
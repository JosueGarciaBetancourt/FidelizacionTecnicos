@extends('layouts.layoutDashboard')

@section('title', 'Recompensas')

@push('styles')
    {{-- 
    <link rel="stylesheet" href="{{ asset('css/recompensasStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalRegistrarNuevaRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEditarRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalInhabilitarRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalRestaurarRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEliminarRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalRegistrarNuevoRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEditarRecompensa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEliminarRecompensa.css') }}">
    --}}
    @vite(['resources/css/recompensasStyle.css'])
    @vite(['resources/css/modalRegistrarNuevaRecompensa.css'])
    @vite(['resources/css/modalRegistrarNuevoTipoRecompensa.css'])
    @vite(['resources/css/modalEditarRecompensa.css'])
    @vite(['resources/css/modalInhabilitarRecompensa.css'])
    @vite(['resources/css/modalRestaurarRecompensa.css'])
    @vite(['resources/css/modalEliminarRecompensa.css'])
@endpush

@section('main-content')
    <div class="recompensasContainer">
        @php
            $isAsisstantLogged = Auth::check() && Auth::user()->idPerfilUsuario == 3;
        @endphp

        @if (!$isAsisstantLogged)
            <div class="firstRowRecompensas">
                <x-btn-create-item onclick="openModal('modalRegistrarNuevaRecompensa')"> Nueva recompensa </x-btn-create-item>
                @include('modals.recompensas.modalRegistrarNuevaRecompensa')
                @include('modals.tiposRecompensas.modalRegistrarNuevoTipoRecompensa')

                <x-btn-edit-item onclick="openModal('modalEditarRecompensa')"> Editar </x-btn-edit-item>
                @include('modals.recompensas.modalEditarRecompensa')

                <x-btn-disable-item onclick="openModal('modalInhabilitarRecompensa')"> Inhabilitar </x-btn-disable-item>
                @include('modals.recompensas.modalInhabilitarRecompensa')

                <x-btn-recover-item onclick="openModal('modalRestaurarRecompensa')"> Habilitar </x-btn-recover-item>
                @include('modals.recompensas.modalRestaurarRecompensa')

                <x-btn-delete-item onclick="openModal('modalEliminarRecompensa')"> Eliminar </x-btn-delete-item>
                @include('modals.recompensas.modalEliminarRecompensa')
            </div>
        @endif

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
                        <td class="celda-centered celdaRecompensa">
							<span style="color: {{ $recompensa->colorTexto_Recompensa }}; background-color: {{ $recompensa->colorFondo_Recompensa }};">
                                {{ $recompensa->nombre_Recompensa }}
							</span> 
                        </td>
                        <td>{{ $recompensa->descripcionRecompensa }}</td>
                        <td class="celda-centered">{{ $recompensa->costoPuntos_Recompensa }}</td>
                        <td @class(['celda-centered', 'fewStock' => $recompensa->stock_Recompensa <= config('settings.unidadesRestantesRecompensasNotificacion')])>
                            {{ $recompensa->stock_Recompensa }}</td>
                        {{-- <td class="celda-centered">{{ $recompensa->stock_Recompensa }}</td> --}}
                        <td class="celda-centered">{{ $recompensa->created_at}}</td>
                        <td class="celda-centered">{{ $recompensa->updated_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
            :idSuccesModal="'successModalRecompensaDisable'"
            :message="'Recompensa inhabilitada correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalRecompensaRestaurada'"
            :message="'Recompensa habilitada correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalRecompensaEliminada'"
            :message="'Recompensa eliminada correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalTipoRecompensaGuardado'"
            :message="'Tipo de recompensa guardado correctamente'"
        />

        <x-modalFailedAction 
            :idErrorModal="'errorModalRecompensaDisable'"
            :message="'La recompensa no puede ser inhabilitada porque hay canjes ó solicitudes de canje asociados'"
        />

        <x-modalFailedAction 
            :idErrorModal="'errorModalRecompensaDelete'"
            :message="'La recompensa no puede ser eliminada porque hay canjes ó solicitudes de canje asociados'"
        />

        <x-modalFailedAction 
            :idErrorModal="'errorModalTipoRecompensa'"
            :message="'No puedes realizar esta acción'"
        />
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/modalRegistrarNuevaRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalRegistrarNuevoTipoRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalEditarRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalInhabilitarRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalRestaurarRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalEliminarRecompensa.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('successRecompensaStore'))
                openModal('successModalRecompensaGuardada');
            @endif
            @if(session('successRecompensaUpdate'))
                openModal('successModalRecompensaActualizada');
            @endif
            @if(session('successRecompensaDisable'))
                openModal('successModalRecompensaDisable');
            @endif
            @if(session('successRecompensaRestaurada'))
                openModal('successModalRecompensaRestaurada');
            @endif
            @if(session('successRecompensaDelete'))
                openModal('successModalRecompensaEliminada');
            @endif
            @if(session('successTipoRecompensaStore'))
                openModal('successModalTipoRecompensaGuardado');
            @endif
            @if(session('errorRecompensaDisable'))
                justOpenModal('errorModalRecompensaDisable');
            @endif
            @if(session('errorRecompensaDelete'))
                justOpenModal('errorModalRecompensaDelete');
            @endif
        });
    </script>
@endpush
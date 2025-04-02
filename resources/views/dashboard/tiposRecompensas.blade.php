@extends('layouts.layoutDashboard')

@section('title', 'Tipos de Recompensas')

@push('styles')
	@vite(['resources/css/tiposRecompensasStyle.css'])
	@vite(['resources/css/modalRegistrarNuevoTipoRecompensa.css'])
    @vite(['resources/css/modalEditarTipoRecompensa.css'])
    @vite(['resources/css/modalEliminarTipoRecompensa.css'])
@endpush

@section('main-content')
    <div class="tiposRecompensasContainer">
        @php
            $isAsisstantLogged = Auth::check() && Auth::user()->idPerfilUsuario == 3;
        @endphp

        @if (!$isAsisstantLogged)
            <div class="firstRowTiposRecompensas">
                <x-btn-create-item onclick="openModal('modalRegistrarNuevoTipoRecompensa')"> 
                    Nuevo tipo de recompensa
                </x-btn-create-item>
                @include('modals.tiposRecompensas.modalRegistrarNuevoTipoRecompensa')

                <x-btn-edit-item onclick="openModal('modalEditarTipoRecompensa')"> Editar </x-btn-edit-item>
                @include('modals.tiposRecompensas.modalEditarTipoRecompensa')

                <x-btn-delete-item onclick="openModal('modalEliminarTipoRecompensa')"> Eliminar </x-btn-delete-item>
                @include('modals.tiposRecompensas.modalEliminarTipoRecompensa')
            </div>
        @endif

        <div class="secondRowTiposRecompensas">
            <table id="tblTiposRecompensas">
                <thead>
                    <tr>
                        <th class="celda-centered">#</th>
                        <th class="celda-centered">Código</th>
                        <th class="celda-centered">Nombre</th>
                        <th id="idCeldaDescripcionTipoRecompensa">Descripción</th>
                        <th class="celda-centered date">Fecha y Hora de creación</th>
                        <th class="celda-centered date">Fecha y Hora de actualización</th> 
                    </tr>
                </thead>
                <tbody>
                    @php
                        $contador = 1;
                    @endphp
                    @foreach ($tiposRecompensas as $tipoRecom)
                    <tr>
                        <td class="celda-centered">{{ $contador++ }}</td> 
                        <td class="celda-centered">{{ $tipoRecom->codigoTipoRecompensa }}</td>
                        <td class="celda-centered celdaTipoRecompensa">
							<span style="color: {{ $tipoRecom->colorTexto_TipoRecompensa }}; background-color: {{ $tipoRecom->colorFondo_TipoRecompensa }};">
                                {{ $tipoRecom->nombre_TipoRecompensa }}
							</span> 
                        </td>
						<td>{{ $tipoRecom->descripcion_TipoRecompensa}}</td>
                        <td class="celda-centered">{{ $tipoRecom->created_at}}</td>
                        <td class="celda-centered">{{ $tipoRecom->updated_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-modalSuccessAction 
            :idSuccesModal="'successModalTipoRecompensaStore'"
            :message="'Tipo de Recompensa guardado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalTipoRecompensaUpdate'"
            :message="'Tipos de Recompensa actualizado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalTipoRecompensaDisable'"
            :message="'TiposRecompensa inhabilitado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalTipoRecompensaEnable'"
            :message="'Tipos de Recompensa habilitado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalTipoRecompensaDelete'"
            :message="'Tipo de Recompensa eliminado correctamente'"
        />

        <x-modalFailedAction 
            :idErrorModal="'errorModalTipoRecompensa'"
            :message="'No puede realizar esta acción porque hay datos asociados al Tipo de Recompensa'"
        />
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/modalRegistrarNuevoTipoRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalEditarTipoRecompensa.js') }}"></script>
    <script src="{{ asset('js/modalEliminarTipoRecompensa.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('successTipoRecompensaStore'))
                openModal('successModalTipoRecompensaStore');
            @endif
            @if(session('successTipoRecompensaUpdate'))
                openModal('successModalTipoRecompensaUpdate');
            @endif
            @if(session('successTipoRecompensaDisable'))
                openModal('successModalTipoRecompensaDisable');
            @endif
            @if(session('successTipoRecompensaEnable'))
                openModal('successModalTipoRecompensaEnable');
            @endif
            @if(session('successTipoRecompensaDelete'))
                openModal('successModalTipoRecompensaDelete');
            @endif
        });
    </script>
@endpush
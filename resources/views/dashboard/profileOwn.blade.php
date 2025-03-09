@extends('layouts.layoutDashboard')

@section('title', 'Perfil de usuario')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profileOwn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalCrearUsuario.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEditarDominioCorreo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEditarUsuario.css') }}">
@endpush

@section('main-content')
    <div class="profileOwnContainer">
        @if (Auth::user()->email === config('settings.adminEmail'))
            <div class="firstRow">
                <x-btn-create-item onclick="openModalCrearUsuario('modalCrearUsuario')"> 
                    Nuevo usuario 
                </x-btn-create-item>
                @include('modals.profile.modalCrearUsuario')
                <x-btn-edit-item onclick="openModalEditarDominio('modalEditarDominioCorreo')"> 
                    Editar dominio de correo 
                </x-btn-edit-item>
                @include('modals.profile.modalEditarDominioCorreo')
            </div>
        @endif

        <section class="cardContainer">
            <div class="cardTitle">Listado de usuarios</div>
            <div class="cardBody">
                <table class="cardTable">
                    <thead>
                        <tr>
                            <th class="celda-centered">#</th>
                            <th class="celda-lefted">Email (inicio de sesión)</th>
                            <th class="celda-lefted">Nombre</th>
                            <th class="celda-centered">Perfil</th>
                            <th class="celda-centered"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $contador = 1;
                            $isAdminProfile = Auth::user()->email === config('settings.adminEmail');
                        @endphp
                        @foreach ($users as $user)
                            <tr>
                                <td class="celda-centered">{{ $contador++ }}</td> 
                                <td class="celda-lefted email">{{ $user->email }}</td>
                                <td class="celda-lefted">{{ $user->name }}</td>
                                <td class="celda-centered" >{{ $user->nombre_PerfilUsuario }}</td>
                                <td class="celda-centered celda-btnAcciones">
                                    @php
                                        $isNotAdminUser = $user->email !== config('settings.adminEmail');
                                        $isUserEnabled = is_null($user->deleted_at);
                                    @endphp
                                
                                    @if ($isUserEnabled)
                                        <button class="edit-btn" onclick="openModalEditarUsuario(this, {{ json_encode($users) }})">Editar</button>
                                        @if ($isNotAdminUser && $isAdminProfile)
                                            <button class="disable-btn" onclick="openModalInhabilitarUsuario(this, {{ json_encode($users) }})">Inhabilitar</button>
                                            <button class="delete-btn" onclick="openModalEliminarUsuario(this, {{ json_encode($users) }})">Eliminar</button>
                                        @endif
                                    @else
                                        @if ($isNotAdminUser && $isAdminProfile)
                                            <button class="enable-btn" onclick="openModalHabilitarUsuario(this, {{ json_encode($users) }})">Habilitar</button>
                                        @endif
                                    @endif
                                </td> 
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        
		@include('modals.profile.modalEditarUsuario')
       
        <x-modalConfirmAction
			:idConfirmModal="'modalConfirmActionPerfilUsuario'"
			:message="'¿Está seguro de esta acción?'"
		/>

        <x-modalSuccessAction 
            :idSuccesModal="'successModalUsuarioGuardado'"
            :message="'Usuario guardado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalDominioCorreoActualizado'"
            :message="'Dominio de correo actualizado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalUsuarioActualizado'"
            :message="'Usuario actualizado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalUsuarioHabilitado'"
            :message="'Usuario habilitado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalUsuarioInhabilitado'"
            :message="'Usuario inhabilitado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalUsuarioEliminado'"
            :message="'Usuario eliminado correctamente'"
        />

        <x-modalFailedAction 
            :idErrorModal="'errorModalPerfilUsuario'"
            :message="'El usuario seleccionado no pudo ser eliminado'"
        />
    </div>
@endsection

@push('scripts')
    <script src="{{asset('js/profileOwn.js')}}"></script>
    <script src="{{asset('js/modalCrearUsuario.js')}}"></script>
    <script src="{{asset('js/modalEditarUsuario.js')}}"></script>
    <script src="{{asset('js/modalEditarDominioCorreo.js')}}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('successUsuarioStore'))
                openModal('successModalUsuarioGuardado');
            @endif
            @if(session('successUsuarioUpdate'))
                openModal('successModalUsuarioActualizado');
            @endif
            @if(session('successDominioCorreoUpdate'))
				openModal('successModalDominioCorreoActualizado');
			@endif

            if (sessionStorage.getItem('usuarioInhabilitado') === 'true') {
                openModal('successModalUsuarioInhabilitado');
                sessionStorage.removeItem('usuarioInhabilitado');
            }

            if (sessionStorage.getItem('usuarioHabilitado') === 'true') {
                openModal('successModalUsuarioHabilitado');
                sessionStorage.removeItem('usuarioHabilitado');
            }

            if (sessionStorage.getItem('usuarioEliminado') === 'true') {
                openModal('successModalUsuarioEliminado');
                sessionStorage.removeItem('usuarioEliminado');
            }
        });
    </script>
@endpush
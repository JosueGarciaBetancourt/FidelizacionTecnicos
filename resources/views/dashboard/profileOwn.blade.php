@extends('layouts.layoutDashboard')

@section('title', 'Perfil de usuario')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profileOwn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalEditarUsuario.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modalCrearUsuario.css') }}">
@endpush

@section('main-content')
    <div class="profileOwnContainer">
        @if (Auth::user()->email === "admin@dimacof.com")
            <div class="firstRow">
                <x-btn-create-item onclick="openModal('modalCrearUsuario')"> 
                    Nuevo usuario 
                </x-btn-create-item>
                @include('modals.profile.modalCrearUsuario')
            </div>
        @endif
        <section class="cardContainer">
            <div class="cardTitle">Listado de usuarios</div>
            <div class="cardBody">
                <table class="cardTable">
                    <thead>
                        <tr>
                            <th class="celda-centered">#</th>
                            <th class="celda-lefted">Email</th>
                            <th class="celda-lefted">Nombre</th>
                            <th class="celda-centered">Perfil</th>
                            <th class="celda-centered"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $contador = 1;
                        @endphp
                        @foreach ($users as $user)
                            <tr>
                                <td class="celda-centered">{{ $contador++ }}</td> 
                                <td class="celda-lefted email">{{ $user->email }}</td>
                                <td class="celda-lefted">{{ $user->name }}</td>
                                <td class="celda-centered" >{{ $user->nombre_PerfilUsuario }}</td>
                                <td class="celda-centered celda-btnAcciones">
                                    @if ($user->email !== "admin@dimacof.com" || Auth::user()->email === "admin@dimacof.com")
                                        <button class="edit-btn" onclick="openModalEditarUsuario(this, {{ json_encode($users) }})">Editar</button>
                                    @endif
                                    @if ($user->email !== "admin@dimacof.com" && Auth::user()->email === "admin@dimacof.com")
                                        <button class="delete-btn" onclick="openModalEliminarUsuario(this, {{ json_encode($users) }}, )">Eliminar</button>
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
			:idConfirmModal="'modalConfirmActionEliminarUsuario'"
			:message="'¿Está seguro de eliminar el usuario?'"
		/>

        <x-modalSuccessAction 
            :idSuccesModal="'successModalUsuarioGuardado'"
            :message="'Usuario guardado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalUsuarioActualizado'"
            :message="'Usuario actualizado correctamente'"
        />

        <x-modalSuccessAction 
            :idSuccesModal="'successModalUsuarioEliminado'"
            :message="'Usuario eliminado correctamente'"
        />
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{asset('js/envUtil.js')}}"></script>
    <script src="{{asset('js/profileOwn.js')}}"></script>
    <script src="{{asset('js/modalCrearUsuario.js')}}"></script>
    <script src="{{asset('js/modalEditarUsuario.js')}}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('successUsuarioStore'))
                openModal('successModalUsuarioGuardado');
            @endif
            @if(session('successUsuarioUpdate'))
                openModal('successModalUsuarioActualizado');
            @endif
            // Verificar si la bandera existe en sessionStorage
            if (sessionStorage.getItem('usuarioEliminado') === 'true') {
                // Abrir el modal de éxito
                openModal('successModalUsuarioEliminado');
                // Eliminar la bandera para que no se repita
                sessionStorage.removeItem('usuarioEliminado');
            }
        });
    </script>
@endpush
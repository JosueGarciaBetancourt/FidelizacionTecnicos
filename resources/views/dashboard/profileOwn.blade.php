@extends('layouts.layoutDashboard')

@section('title', 'Perfil de usuario')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profileOwn.css') }}">
@endpush

@section('main-content')
    <div class="profileOwnContainer">

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
                                <td class="celda-centered" >{{ $user->profile }}</td>
                                <td class="celda-centered celda-btnAcciones">
                                    <button class="edit-btn" onclick="openModalEditarUsuario(this, {{ json_encode($users) }})">Editar</button>
                                    <button class="delete-btn" onclick="openModalEliminarUsuario(this, {{ json_encode($users) }})">Eliminar</button>
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
        
        <!-- Sección de Información de Perfil -->
        <div class="section-container">
            <h2 class="section-title">Información de perfil</h2>
            <p class="section-description">
                Actualiza la información de perfil y dirección de correo electrónico de tu cuenta
            </p>

            <form action="/profile/update" method="POST">
                <div class="form-group">
                    <label class="form-label" for="name">Nombre</label>
                    <input class="form-input" type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Correo electrónico</label>
                    <input class="form-input" type="email" id="email" name="email" required>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
        <!-- Sección de Actualización de Contraseña -->
        <div class="section-container">
            <h2 class="section-title">Actualizar contraseña</h2>
            <p class="section-description">
                Asegúrate de que tu cuenta utilice una contraseña larga y aleatoria para mantenerse segura
            </p>

            <form action="/password/update" method="POST">
                <div class="form-group">
                    <label class="form-label" for="current_password">Contraseña actual</label>
                    <input class="form-input" type="password" id="current_password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="new_password">Contraseña nueva</label>
                    <input class="form-input" type="password" id="new_password" name="new_password" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                    <input class="form-input" type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            </div>

        <!-- Sección de Eliminación de Cuenta -->
        <div class="section-container">
                <h2 class="section-title">Eliminar cuenta</h2>
                <p class="section-description">
                    Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente. 
                    Antes de eliminar su cuenta, descargue cualquier dato o información que desee conservar.
                </p>

                <button class="btn btn-danger" onclick="showDeleteModal()">Eliminar cuenta</button>
            </div>
        </div>
        <!-- Modal de Confirmación de Eliminación -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <h2 class="section-title">¿Estás seguro de que quieres eliminar tu cuenta?</h2>
                <p class="section-description">
                    Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente. 
                    Ingrese su contraseña para confirmar que desea eliminar permanentemente su cuenta.
                </p>

                <form action="/profile/delete" method="POST">
                    <div class="form-group">
                        <label class="form-label" for="delete_password">Contraseña</label>
                        <input class="form-input" type="password" id="delete_password" name="password" required>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="button" class="btn btn-secondary" onclick="hideDeleteModal()">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar cuenta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('js/profileOwn.js')}}"></script>

    <script>
        function showDeleteModal() {
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
@endpush
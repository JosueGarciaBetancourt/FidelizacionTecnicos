<div class="modal first modalEditarUsuario" id="modalEditarUsuario">
    <div class="modal-dialog modalEditarUsuario">
        <div class="modal-content modalEditarUsuario">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
                <button class="close" onclick="closeModal('modalEditarUsuario')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyAgregarNuevoTecnico">
                <div class="section-navbar">
                    <a href="#" class="section-tab active">Datos de Usuario</a>
                    <a href="#" class="section-tab">Datos personales</a>
                </div>

                <form id="formEditarUsuario" action="{{ route('usuarios.update') }}" method="POST">
                    @csrf
                    @method('patch')

                    @php 
                        $usersDB = $users;
                    @endphp

                    <div class="form-group gap">
                        <div class="group-items">
                            <label class="secondary-label" id="dniLabel" for="nameInput">Nombre</label>
                            <input class="input-item" type="text" id="nameInput" placeholder="Ingresar nombre" maxlength="30" 
                                value="{{ Auth::user()->name}}" oninput="validateRealTimeInputLength(this, 30)" name="name">
                        </div>
                    
                        <div class="group-items">
                            <label class="secondary-label" id="nameLabel"  for="emailInput">Correo electrónico</label>
                            <input class="input-item blocked" type="text" id="emailInput" value="{{ Auth::user()->email}}" name="email" readOnly
                                oninput="validateRealTimeInputLength(this, 60)">
                        </div>
                    </div>

                    <div class="form-group gap">
                        <div class="group-items">
                            <label class="secondary-label" id="nameLabel" for="passwordInput" >Contraseña</label>
                            <div class="passwordInputContainer">
                                <input class="passwordInput" type="password" id="passwordInput" placeholder="" autocomplete="off"
                                    maxlength="20">
                                <span class="viewPasswordIcon material-symbols-outlined">visibility</span>
                            </div>
                        </div>
    
                        <div class="group-items">
                            <label class="secondary-label" id="nameLabel"  for="confirmPasswordInput">Confirmar Contraseña</label>
                            <div class="passwordInputContainer">
                                <input class="passwordInput" type="password" id="confirmPasswordInput" placeholder="" autocomplete="off"
                                    maxlength="20" name="password">
                                <span class="viewPasswordIcon material-symbols-outlined">visibility</span>
                            </div>
                        </div>
                    </div>
 
                    <div class="group-items">
                        <label class="secondary-label noEditable" id="phoneLabel" for="phoneInput">Perfil</label>
                        <x-onlySelect-input 
                            :idInput="'perfilUsuarioInput'"
                            :inputClassName="'onlySelectInput long'"
                            :placeholder="'Seleccionar perfil'"
                            :options="$nombresPerfilesUsuarios"
                        />
                    </div>

                    <div class="form-group start">
                        <span class="inline-alert-message" id="multiMessageError"> multiMessageError </span>      
                    </div>

                    <input type="hidden" name="origin" id="origin">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditarUsuario')">Cancelar</button>
                <button type="button" class="btn btn-primary" 
                        onclick="guardarmodalEditarUsuario('modalEditarUsuario', 'formEditarUsuario'">Guardar</button>
            </div>
        </div>
    </div>
</div>


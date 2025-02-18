<div class="modal first modalCrearUsuario" id="modalCrearUsuario">
    <div class="modal-dialog modalCrearUsuario">
        <div class="modal-content modalCrearUsuario">
            <div class="modal-header">
                <h5 class="modal-title">Crear Usuario</h5>
                <button class="close noUserSelect" onclick="closeModal('modalCrearUsuario')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyAgregarNuevoTecnico">
                <div class="section-navbar">
                    <a href="#" class="section-tab crear active">Datos de Usuario</a>
                    <a href="#" class="section-tab crear">Datos personales</a>
                </div>

                <form id="formCrearUsuario" action="{{ route('usuarios.store') }}" method="POST">
                    @csrf

                    @php 
                        $usersDB = $users;
                        $perfilesUsuariosDB = $perfilesUsuarios;
                        $nameInput = "nameInputCrearUsuario";
                        $emailInput = "emailInputCrearUsuario";
                        $emailTooltip = "idEmailTooltipCrear";
                        $passwordInput = "passwordInputCrearUsuario";
                        $confirmPasswordInput = "confirmPasswordInputCrearUsuario";
                        $perfilUsuarioSelect = "perfilUsuarioSelectCrearUsuario";
                        $perfilUsuarioInput = "perfilUsuarioInputCrearUsuario";
                        $confirmPasswordTooltip = "idConfirmPasswordTooltipCrear";
                    @endphp
                    
                    <div class="crearUsuarioContainer">
                        <section class="sectionContent crear active">
                            <div class="form-group gap">
                                <div class="group-items">
                                    <label class="secondary-label" id="dniLabel" for="{{ $nameInput }}">Nombre</label>
                                    <input class="input-item" type="text" id="{{ $nameInput }}" placeholder="Ingresar nombre" maxlength="30" 
                                            oninput="validateRealTimeInputLength(this, 30)" name="name" required value="josue">
                                </div>
                            
                                <div class="group-items">
                                    <label class="secondary-label" id="nameLabel"  for="{{ $emailInput }}">Correo electrónico</label>
                                    <div class="tooltip-container">
                                        <span class="tooltip red" id="{{ $emailTooltip }}"></span>
                                    </div>
                                    <input class="input-item" type="email" id="{{ $emailInput }}"  maxlength="30" name="email"required value="josue@dimacof.com">
                                </div>
                            </div>
    
                            <div class="form-group gap">
                                <div class="group-items">
                                    <label class="secondary-label" id="nameLabel" for="{{ $passwordInput }}" >Contraseña</label>
                                    <div class="passwordInputContainer">
                                        <input class="passwordInput" type="password" id="{{ $passwordInput }}" autocomplete="off"
                                            maxlength="20" value="12345678">
                                        <span class="viewPasswordIcon material-symbols-outlined noUserSelect" onclick="togglePasswordVisibility(this, '{{ $passwordInput }}')">
                                            visibility_off
                                        </span>                           
                                    </div>
                                </div>
            
                                <div class="group-items">
                                    <label class="secondary-label" id="nameLabel"  for="{{ $confirmPasswordInput }}">Confirmar Contraseña</label>
                                    <div class="tooltip-container">
                                        <span class="tooltip red" id="{{ $confirmPasswordTooltip }}">La confirmación de contraseña no coincide.</span>
                                    </div>
                                    <div class="passwordInputContainer">
                                        <input class="passwordInput" type="password" id="{{ $confirmPasswordInput }}" autocomplete="off"
                                            maxlength="20" name="password" value="12345678">
                                        <span class="viewPasswordIcon material-symbols-outlined noUserSelect" onclick="togglePasswordVisibility(this, '{{ $confirmPasswordInput }}')">
                                            visibility_off
                                        </span>
                                    </div>
                                </div>
                            </div>
        
                            <div class="group-items">
                                <label class="secondary-label noEditable" id="perfilUsuarioLabel" for="{{ $perfilUsuarioInput }}">Perfil</label>
                                    <x-onlySelectNoCleanable-input
                                        :idSelect="$perfilUsuarioSelect"
                                        :idInput="$perfilUsuarioInput"
                                        :defaultValue="'Administrador'"
                                        :options="$nombresPerfilesUsuarios"
                                        :onSelectFunction="'selectOptionPerfilUsuarioCrear'"
                                        :extraArgOnClickFunction="$perfilesUsuariosDB"
                                        :isExtraArgJson="true"
    
                                        :inputClassName="'onlySelectInput long'"
                                        :disabled="false"
                                    />
                                <input type="hidden" id="idPerfilUsuarioInputCrear" name="idPerfilUsuario" value="1" readonly> 
                            </div>
    
                            <div class="form-group start">
                                <span class="inline-alert-message" id="crearUsuarioMessageError"> multiMessageError </span>      
                            </div>
                        </section>
    
                        <section class="sectionContent crear">
                            Aquí irán los datos personales del usuario
                        </section>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModalProfileOwn('modalCrearUsuario')">Cancelar</button>
                <button type="button" class="btn btn-primary" 
                        onclick="guardarModalCrearUsuario('modalCrearUsuario', 'formCrearUsuario')">Guardar</button>
            </div>
        </div>
    </div>
</div>


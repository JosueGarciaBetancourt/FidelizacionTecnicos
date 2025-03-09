<div class="modal first modalEditarUsuario" id="modalEditarUsuario">
    <div class="modal-dialog modalEditarUsuario">
        <div class="modal-content modalEditarUsuario">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
                <button class="close noUserSelect" onclick="closeModalProfileOwn('modalEditarUsuario')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyAgregarNuevoTecnico">
                <div class="section-navbar">
                    <a href="#" class="section-tab editar active">Datos de Usuario</a>
                    <a href="#" class="section-tab editar">Datos personales</a>
                </div>

                <form id="formEditarUsuario" action="{{ route('usuarios.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    @php 
                        $usersDB = $users;
                        $perfilesUsuariosDB = $perfilesUsuarios;
                        $idUserInput = "idUser";
                        $nameInput = "nameInputEditarUsuario";
                        $emailTextInput = "emailTextInputEditarUsuario";
                        $emailDomain = $userEmailDomain;
                        $emailHiddenInput = "emailHiddenInputEditarUsuario";
                        $emailTooltip = "correoTooltipEditarUsuario";
                        $passwordInput = "passwordInputEditarUsuario";
                        $confirmPasswordInput = "confirmPasswordInputEditarUsuario";
                        $perfilUsuarioSelect = "perfilUsuarioSelectEditarUsuario";
                        $perfilUsuarioInput = "perfilUsuarioInputEditarUsuario";
                        $confirmPasswordTooltip = "idConfirmPasswordTooltip";
                        $editarDatosUsuarioMessageError = "editarDatosUsuarioMessageError";
                        // Datos personales section
                        $DNIInput = "DNIInputEditarUsuario";
                        $personalNameInput = "personalNameInputEditarUsuario";
                        $surnameInput = "surnameInputEditarUsuario";
                        $fechaNacimientoInput = "fechaNacimientoInputEditarUsuario";
                        $dateMessageEditarUsuarioError = "dateMessageEditarUsuarioError";
                        $correoPersonalInput = "correoPersonalInputEditarUsuario";
                        $correoPersonalTooltip = "correoPersonalTooltipEditarUsuario";
                        $celularPersonalInput = "celularPersonalInputEditarUsuario";
                        $celularCorporativoInput = "celularCorporativoInputEditarUsuario";
                        $editarDatosPersonalesMessageError = "editarDatosPersonalesMessageError";
                    @endphp

                    <input type="hidden" id="{{ $idUserInput }}" name="id" readonly> 

                    <div class="editarUsuarioContainer">
                        <section class="sectionContent editar active">
                            <div class="editarContent">
                                <div class="form-group gap">
                                    <div class="group-items">
                                        <label class="primary-label" id="nameEditarLabel" for="{{ $nameInput }}">Nombre</label>
                                        <input class="input-item" type="text" id="{{ $nameInput }}" placeholder="Ingresar nombre" maxlength="100" name="name" required>
                                    </div>
                                
                                    <div class="group-items">
                                        <label class="primary-label" id="emailEditarLabel"  for="{{ $emailTextInput }}">Correo electrónico</label>
                                        <div class="tooltip-container">
                                            <span class="tooltip red" id="{{ $emailTooltip }}"></span>
                                        </div>
                                        <div class="inputContainer" id="idEmailTextInputContainer">
                                            <input class="emailTextInput" type="text" id="{{ $emailTextInput }}" maxlength="70" value="josue" 
                                                oninput="fillHiddenEmailInput(this, '{{ $emailHiddenInput }}')" placeholder="vendedor_123" required>
                                            <span class="emailDomain">{{ '@' . $emailDomain }}</span>
                                        </div>
                                        <input type="hidden" id="{{ $emailHiddenInput }}" maxlength="100" name="email" readonly required>
                                    </div>
                                </div>
            
                                <div class="form-group gap">
                                    <div class="group-items">
                                        <label class="primary-label" id="passwordEditarLabel" for="{{ $passwordInput }}" >Contraseña</label>
                                        <div class="passwordInputContainer">
                                            <input class="passwordInput" type="password" id="{{ $passwordInput }}" autocomplete="off" maxlength="20">
                                            <span class="viewPasswordIcon material-symbols-outlined noUserSelect" onclick="togglePasswordVisibility(this, '{{ $passwordInput }}')">
                                                visibility_off
                                            </span>                           
                                        </div>
                                    </div>
                
                                    <div class="group-items">
                                        <label class="primary-label" id="confirmPasswordEditarLabel"  for="{{ $confirmPasswordInput }}">Confirmar Contraseña</label>
                                        <div class="tooltip-container">
                                            <span class="tooltip red" id="{{ $confirmPasswordTooltip }}">La confirmación de contraseña no coincide.</span>
                                        </div>
                                        <div class="passwordInputContainer">
                                            <input class="passwordInput" type="password" id="{{ $confirmPasswordInput }}" autocomplete="off" maxlength="20" name="password">
                                            <span class="viewPasswordIcon material-symbols-outlined noUserSelect" onclick="togglePasswordVisibility(this, '{{ $confirmPasswordInput }}')">
                                                visibility_off
                                            </span>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="form-group gap">
                                    <div class="group-items">
                                        <label class="primary-label" id="perfilUsuarioEditarLabel" for="{{ $perfilUsuarioInput }}">Perfil</label>
                                            <x-onlySelectNoCleanable-input
                                                    :idSelect="$perfilUsuarioSelect"
                                                    :idInput="$perfilUsuarioInput"
                                                    :defaultValue="'Administrador'"
                                                    :options="$nombresPerfilesUsuariosNoAdmin"
                                                    :onSelectFunction="'selectOptionPerfilUsuarioEdit'"
                                                    :extraArgOnClickFunction="$perfilesUsuariosDB"
                                                    :isExtraArgJson="true"
                                                    :inputClassName="'onlySelectInput long blocked'"
                                                    :spanClassName="'blocked'"
                                                    :disabled="false"
                                                    :containerClassName="'noFocusBorder blocked'"
                                            />
                                        <input type="hidden" id="idPerfilEditarUsuarioInput" name="idPerfilUsuario" readonly> 
                                    </div>

                                    <span class="limit-text"> 
                                        El perfil de asistente tiene permisos únicamente para visualizar en el sistema.
                                    </span>  
                                </div>
                            </div>

                            <span class="inline-alert-message" id="{{ $editarDatosUsuarioMessageError }}"> multiMessageError </span>
                        </section>
                        
                        <section class="sectionContent editar">
                            <div class="editarContent">
                                <div class="form-group gap">
                                    <div class="group-items">
                                        <label class="primary-label" id="DNIEditarLabel" for="{{ $DNIInput }}">DNI</label>
                                        <input class="input-item" type="number" id="{{ $DNIInput }}" placeholder="12345678"
                                            oninput="validateRealTimeInputLength(this, 8), validateNumberRealTime(this)" name="DNI" value="11122233">
                                    </div>
                                
                                    <div class="group-items">
                                        <label class="primary-label" id="personalNameEditarLabel"  for="{{ $personalNameInput }}">Nombres</label>
                                        <input class="input-item" type="text" id="{{ $personalNameInput }}" name="personalName" value="Josué Daniel">
                                    </div>

                                    <div class="group-items">
                                        <label class="primary-label" id="surnameEditarLabel"  for="{{ $surnameInput }}">Apellidos</label>
                                        <input class="input-item" type="text" id="{{ $surnameInput }}" name="surname" value="García Betancourt">
                                    </div>
                                </div>
                            
                                <div class="form-group gap">
                                    <div class="group-items">
                                        <label class="primary-label" id="fechaNacimientoLabel"  for="{{ $fechaNacimientoInput }}">Fecha de nacimiento</label>
                                        <input class="input-item" type="date" id="{{ $fechaNacimientoInput }}" name="fechaNacimiento" value="2002-12-12">
                                    </div>
                                 
                                    <span class="inline-alert-message" id="{{ $dateMessageEditarUsuarioError }}"> dateMessageEditarUsuarioError </span>      
                                </div>
                                
                                <div class="form-group gap">
                                    <div class="group-items">
                                        <label class="primary-label" id="correoElectronicoPersonalLabel"  for="{{ $correoPersonalInput }}">Correo electrónico personal</label>
                                        <div class="tooltip-container">
                                            <span class="tooltip red" id="{{ $correoPersonalTooltip }}"></span>
                                        </div>
                                        <input class="input-item profileEmail" type="email" id="{{ $correoPersonalInput }}"  maxlength="100" name="correoPersonal" value="josue@gmail.com">
                                    </div>
    
                                    <div class="group-items">
                                        <label class="primary-label" id="celularPersonalLabel" for="{{ $celularPersonalInput }}">Celular personal</label>
                                        <input class="input-item" type="number" id="{{ $celularPersonalInput }}" placeholder="999888777"
                                            oninput="validateRealTimeInputLength(this, 9), validateNumberRealTime(this)" name="celularPersonal" value="964866527">
                                    </div>
    
                                    <div class="group-items">
                                        <label class="primary-label" id="celularCorporativoLabel" for="{{ $celularCorporativoInput }}">Celular corporativo</label>
                                        <input class="input-item" type="number" id="{{ $celularCorporativoInput }}" placeholder="999888777"
                                            oninput="validateRealTimeInputLength(this, 9), validateNumberRealTime(this)" name="celularCorporativo" value="999888777">
                                    </div>
                                </div>
                            </div>

                            <span class="inline-alert-message" id="{{ $editarDatosPersonalesMessageError }}"> multiMessageError </span>
                        </section>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModalProfileOwn('modalEditarUsuario')">Cancelar</button>
                <button type="button" class="btn btn-primary" 
                        onclick="guardarModalEditarUsuario('modalEditarUsuario', 'formEditarUsuario')">Guardar</button>
            </div>
        </div>
    </div>
</div>


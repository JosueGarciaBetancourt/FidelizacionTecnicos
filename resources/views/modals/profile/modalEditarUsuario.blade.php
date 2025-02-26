<div class="modal first modalEditarUsuario" id="modalEditarUsuario">
    <div class="modal-dialog modalEditarUsuario">
        <div class="modal-content modalEditarUsuario">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
                <button class="close noUserSelect" onclick="closeModal('modalEditarUsuario')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyAgregarNuevoTecnico">
                <div class="section-navbar">
                    <a href="#" class="section-tab editar active">Datos de Usuario</a>
                    <a href="#" class="section-tab editar">Datos personales</a>
                </div>

                <form id="formEditarUsuario" action="{{ route('usuarios.update') }}" method="POST">
                    @csrf
                    @method('patch')

                    @php 
                        $usersDB = $users;
                        $perfilesUsuariosDB = $perfilesUsuarios;
                        $idUserInput = "idUser";
                        $nameInput = "nameInputEditarUsuario";
                        $emailInput = "emailInputEditarUsuario";
                        $passwordInput = "passwordInputEditarUsuario";
                        $confirmPasswordInput = "confirmPasswordInputEditarUsuario";
                        $perfilUsuarioSelect = "perfilUsuarioSelectEditarUsuario";
                        $perfilUsuarioInput = "perfilUsuarioInputEditarUsuario";
                        $confirmPasswordTooltip = "idConfirmPasswordTooltip";
                        // Datos personales section
                        $DNIInput = "DNIInputEditarUsuario";
                        $personalNameInput = "personalNameInputEditarUsuario";
                        $surnameInput = "surnameInputEditarUsuario";
                        $fechaNacimientoInput = "fechaNacimientoInputEditarUsuario";
                        $correoPersonalInput = "correoPersonalInputEditarUsuario";
                        $correoPersonalTooltip = "idEmailTooltipEditar";
                        $celularPersonalInput = "celularPersonalInputEditarUsuario";
                        $celularCorporativoInput = "celularCorporativoInputEditarUsuario";
                    @endphp

                    <div class="editarUsuarioContainer">
                        <section class="sectionContent editar active">
                            <div class="form-group gap">
                                <input type="hidden" id="{{ $idUserInput }}" name="id" readonly> 
                                <div class="group-items">
                                    <label class="secondary-label" id="dniLabel" for="{{ $nameInput }}">Nombre</label>
                                    <input class="input-item" type="text" id="{{ $nameInput }}" placeholder="Ingresar nombre" maxlength="30" 
                                            oninput="validateRealTimeInputLength(this, 30)" name="name">
                                </div>
                            
                                <div class="group-items">
                                    <label class="secondary-label" id="nameLabel"  for="{{ $emailInput }}">Correo electrónico</label>
                                    <input class="input-item blocked" type="email" id="{{ $emailInput }}" readOnly
                                        maxlength="30">
                                </div>
                            </div>
        
                            <div class="form-group gap">
                                <div class="group-items">
                                    <label class="secondary-label" id="nameLabel" for="{{ $passwordInput }}" >Contraseña</label>
                                    <div class="passwordInputContainer">
                                        <input class="passwordInput" type="password" id="{{ $passwordInput }}" autocomplete="off"
                                            maxlength="20">
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
                                            maxlength="20" name="password">
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
                                            :onSelectFunction="'selectOptionPerfilUsuarioEdit'"
                                            :extraArgOnClickFunction="$perfilesUsuariosDB"
                                            :isExtraArgJson="true"
                                            :inputClassName="'onlySelectInput long blocked'"
                                            :spanClassName="'blocked'"
                                            :disabled="false"
                                            :containerClassName="'noFocusBorder blocked'"
                                    />
                                <input type="hidden" id="idPerfilUsuarioInput" name="idPerfilUsuario" readonly> 
                            </div>
        
                            <div class="form-group start">
                                <span class="inline-alert-message" id="editarUsuarioMessageError"> multiMessageError </span>      
                            </div>
                        </section>
                        
                        <section class="sectionContent editar">
                            <div class="form-group gap">
                                <div class="group-items">
                                    <label class="secondary-label" id="DNIEditarLabel" for="{{ $DNIInput }}">DNI</label>
                                    <input class="input-item" type="number" id="{{ $DNIInput }}" placeholder="12345678"
                                        oninput="validateRealTimeInputLength(this, 8), validateNumberRealTime(this)" name="DNI" 
                                        value="11122233" required>
                                </div>
                            
                                <div class="group-items">
                                    <label class="secondary-label" id="personalNameEditarLabel"  for="{{ $personalNameInput }}">Nombres</label>
                                    <input class="input-item" type="text" id="{{ $personalNameInput }}" name="personalName" value="Josué Daniel" required>
                                </div>

                                <div class="group-items">
                                    <label class="secondary-label" id="surnameEditarLabel"  for="{{ $surnameInput }}">Apellidos</label>
                                    <input class="input-item" type="text" id="{{ $surnameInput }}" name="surname" value="García Betancourt" required>
                                </div>
                            </div>
                          
                            <div class="form-group gap">
                                <div class="group-items">
                                    <label class="secondary-label" id="fechaNacimientoEditarLabel"  for="{{ $fechaNacimientoInput }}">Fecha de nacimiento</label>
                                    <input class="input-item" type="date" id="{{ $fechaNacimientoInput }}" name="fechaNacimiento">
                                </div>

                                <div class="group-items">
                                    <label class="secondary-label" id="correoElectronicoPersonalEditarLabel"  for="{{ $correoPersonalInput }}">Correo electrónico personal</label>
                                    <div class="tooltip-container">
                                        <span class="tooltip red" id="{{ $correoPersonalTooltip }}"></span>
                                    </div>
                                    <input class="input-item" type="email" id="{{ $correoPersonalInput }}"  maxlength="30" name="correoPersonal" value="josue@gmail.com" required>
                                </div>
                            </div>
                            
                            <div class="form-group gap">
                                <div class="group-items">
                                    <label class="secondary-label" id="celularPersonalEditarLabel" for="{{ $celularPersonalInput }}">Celular personal</label>
                                    <input class="input-item" type="number" id="{{ $celularPersonalInput }}" placeholder="999888777"
                                        oninput="validateRealTimeInputLength(this, 9), validateNumberRealTime(this)" name="celularPersonal">
                                </div>

                                <div class="group-items">
                                    <label class="secondary-label" id="celularCorporativoEditarLabel" for="{{ $celularCorporativoInput }}">Celular corporativo</label>
                                    <input class="input-item" type="number" id="{{ $celularCorporativoInput }}" placeholder="999888777"
                                        oninput="validateRealTimeInputLength(this, 9), validateNumberRealTime(this)" name="celularCorporativo">
                                </div>
                            </div>

                            <div class="form-group start">
                                <span class="inline-alert-message" id="crearUsuarioMessageError"> multiMessageError </span>      
                            </div>
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


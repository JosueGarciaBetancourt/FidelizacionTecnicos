<div class="modal first modalCrearUsuario" id="modalCrearUsuario">
    <div class="modal-dialog modalCrearUsuario">
        <div class="modal-content modalCrearUsuario">
            <div class="modal-header">
                <h5 class="modal-title">Crear Usuario</h5>
                <button class="close noUserSelect" onclick="closeModalProfileOwn('modalCrearUsuario')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyAgregarNuevoUsuario">
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
                        $emailTextInput = "emailTextInputCrearUsuario";
                        $emailDomain = $userEmailDomain;
                        $emailHiddenInput = "emailHiddenInputCrearUsuario";
                        $emailTooltip = "correoTooltipCrearUsuario";
                        $passwordInput = "passwordInputCrearUsuario";
                        $confirmPasswordInput = "confirmPasswordInputCrearUsuario";
                        $perfilUsuarioSelect = "perfilUsuarioSelectCrearUsuario";
                        $perfilUsuarioInput = "perfilUsuarioInputCrearUsuario";
                        $crearDatosUsuarioMessageError = "crearDatosUsuarioMessageError";
                        // Datos personales section
                        $DNIInput = "DNIInputCrearUsuario";
                        $personalNameInput = "personalNameInputCrearUsuario";
                        $surnameInput = "surnameInputCrearUsuario";
                        $fechaNacimientoInput = "fechaNacimientoInputCrearUsuario";
                        $dateMessageCrearUsuarioError = "dateMessageCrearUsuarioError";
                        $correoPersonalInput = "correoPersonalInputCrearUsuario";
                        $correoPersonalTooltip = "correoPersonalTooltipCrearUsuario";
                        $celularPersonalInput = "celularPersonalInputCrearUsuario";
                        $celularCorporativoInput = "celularCorporativoInputCrearUsuario";
                        $crearDatosPersonalesMessageError = "crearDatosPersonalesMessageError";
                    @endphp
                    
                    <div class="crearUsuarioContainer">
                        <section class="sectionContent crear active">
                            <div class="crearContent">
                                <div class="form-group gap">
                                    <div class="group-items">
                                        <label class="primary-label" id="nameLabel" for="{{ $nameInput }}">Nombre</label>
                                        <input class="input-item" type="text" id="{{ $nameInput }}" placeholder="Ingresar nombre" maxlength="100" name="name" value="josue" required>
                                    </div>
                                
                                    <div class="group-items">
                                        <label class="primary-label" id="emailLabel"  for="{{ $emailTextInput }}">Correo electrónico</label>
                                        <div class="tooltip-container">
                                            <span class="tooltip red" id="{{ $emailTooltip }}"></span>
                                        </div>
                                        <div class="inputContainer">
                                            <input class="emailTextInput" type="text" id="{{ $emailTextInput }}" maxlength="70" value="josue"
                                                oninput="fillHiddenEmailInput(this, '{{ $emailHiddenInput }}')" placeholder="vendedor_123" required>
                                            <span class="emailDomain">{{ '@' . $emailDomain }}</span>
                                        </div>
                                        <input type="hidden" id="{{ $emailHiddenInput }}" maxlength="100" name="email" value="josue{{ '@' . $emailDomain }}" readonly required>
                                    </div>
                                </div>
        
                                <div class="form-group gap">
                                    <div class="group-items">
                                        <label class="primary-label" id="passwordLabel" for="{{ $passwordInput }}" >Contraseña</label>
                                        <div class="passwordInputContainer">
                                            <input class="passwordInput" type="password" id="{{ $passwordInput }}" autocomplete="off" maxlength="20" value="12345678">
                                            <span class="viewPasswordIcon material-symbols-outlined noUserSelect" onclick="togglePasswordVisibility(this, '{{ $passwordInput }}')">
                                                visibility_off
                                            </span>                           
                                        </div>
                                    </div>
                
                                    <div class="group-items">
                                        <label class="primary-label" id="confirmPasswordLabel"  for="{{ $confirmPasswordInput }}">Confirmar Contraseña</label>
                                        <div class="passwordInputContainer">
                                            <input class="passwordInput" type="password" id="{{ $confirmPasswordInput }}" autocomplete="off"
                                                maxlength="20" name="password" value="12345678">
                                            <span class="viewPasswordIcon material-symbols-outlined noUserSelect" onclick="togglePasswordVisibility(this, '{{ $confirmPasswordInput }}')">
                                                visibility_off
                                            </span>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="form-group gap">
                                    <div class="group-items">
                                        <label class="primary-label" id="perfilUsuarioLabel" for="{{ $perfilUsuarioInput }}">Perfil</label>
                                        <x-onlySelectNoCleanable-input
                                            :idSelect="$perfilUsuarioSelect"
                                            :idInput="$perfilUsuarioInput"
                                            :defaultValue="'Vendedor'"
                                            :options="$nombresPerfilesUsuariosNoAdmin"
                                            :onSelectFunction="'selectOptionPerfilUsuarioCrear'"
                                            :extraArgOnClickFunction="$perfilesUsuariosDB"
                                            :isExtraArgJson="true"
                                            :inputClassName="'onlySelectInput long'"
                                            :disabled="false"
                                        />
                                        <input type="hidden" id="idPerfilCrearUsuarioInput" name="idPerfilUsuario" value="2" readonly> 
                                    </div>
                                    
                                
                                    <span class="limit-text"> 
                                        El perfil de asistente tiene permisos únicamente para visualizar en el sistema.
                                    </span>  
                                </div>
                            </div>
                           
                            <span class="inline-alert-message" id="{{ $crearDatosUsuarioMessageError }}"> multiMessageError </span>      
                        </section>
    
                        <section class="sectionContent crear">
                            <div class="crearContent">
                                <div class="form-group gap">
                                    <div class="group-items">
                                        <label class="primary-label" id="DNILabel" for="{{ $DNIInput }}">DNI</label>
                                        <input class="input-item" type="number" id="{{ $DNIInput }}" placeholder="12345678"
                                            oninput="validateRealTimeInputLength(this, 8), validateNumberRealTime(this)" name="DNI" value="11122233">
                                    </div>
                                
                                    <div class="group-items">
                                        <label class="primary-label" id="personalNameLabel"  for="{{ $personalNameInput }}">Nombres</label>
                                        <input class="input-item" type="text" id="{{ $personalNameInput }}" name="personalName" value="Josué Daniel">
                                    </div>
    
                                    <div class="group-items">
                                        <label class="primary-label" id="surnameLabel"  for="{{ $surnameInput }}">Apellidos</label>
                                        <input class="input-item" type="text" id="{{ $surnameInput }}" name="surname" value="García Betancourt">
                                    </div>
                                </div>
                              
                                <div class="form-group gap">
                                    <div class="group-items">
                                        <label class="primary-label" id="fechaNacimientoLabel"  for="{{ $fechaNacimientoInput }}">Fecha de nacimiento</label>
                                        <input class="input-item" type="date" id="{{ $fechaNacimientoInput }}" name="fechaNacimiento" value="2002-12-12">
                                    </div>
                                 
                                    <span class="inline-alert-message" id="{{ $dateMessageCrearUsuarioError }}"> dateMessageCrearUsuarioError </span>      
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

                            <span class="inline-alert-message" id="{{ $crearDatosPersonalesMessageError }}"> multiMessageError </span>      
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


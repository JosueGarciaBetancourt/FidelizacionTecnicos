<div class="modal first modalEditarDominioCorreo" id="modalEditarDominioCorreo">
    <div class="modal-dialog modalEditarDominioCorreo">
        <div class="modal-content modalEditarDominioCorreo">
            <div class="modal-header">
                <h5 class="modal-title">Editar dominio de correo</h5>
                <button class="close noUserSelect" onclick="closeModal('modalEditarDominioCorreo')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEditarDominicoCorreo">
                <form id="formEditarDominicoCorreo" action="{{ route('configuracion.variables') }}" method="POST">
                    @method('PUT')
                    @csrf

                    @php 
                        $emailDomain = $userEmailDomain;
                        $currentDomainInput = "currentDomainInputEditarDominioCorreo";
                        $newDomainInput = "newDomainInputEditarDominioCorreo";
                        $editarDominioCorreoMessageError = "editarDominioCorreoMessageError";
                    @endphp

                    <div class="form-group start paddingY" id="idH5EditarDominicoCorreoContainer">
                        <h5> *Se cambiará el dominio en todos los correos de los usuarios del sistema.</h5>
                    </div>

                    <div class="form-group gap">
                        <div class="group-items">
                            <label class="secondary-label" id="currentDomainLabel" for="{{ $currentDomainInput }}">Dominio actual</label>
                            <input class="input-item blocked" type="text" id="{{ $currentDomainInput }}" maxlength="100" value="{{ $emailDomain }}" disabled>
                        </div>

                        <div class="group-items">
                            <label class="primary-label" id="newDomainLabel" for="{{ $newDomainInput }}">Dominio nuevo</label>
                            <input type="hidden" value="emailDomain" name="key" readonly>
                            <input class="input-item" type="text" id="{{ $newDomainInput }}" maxlength="100" placeholder="dominionuevo.com" name="value" required>
                        </div>
                    </div>

                    <span class="inline-alert-message" id="{{ $editarDominioCorreoMessageError }}"> multiMessageError </span>      
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditarDominioCorreo')">Cancelar</button>
                <button type="button" class="btn btn-primary" 
                        onclick="guardarModalEditarDominioCorreo('modalEditarDominioCorreo', 'formEditarDominicoCorreo')">Guardar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal first"  id="modalRegistrarNuevoOficio">
    <div class="modal-dialog" id="modalRegistrarNuevoOficio-dialog">
        <div class="modal-content" id="modalRegistrarNuevoOficio-content">
            <div class="modal-header">
                <h5 class="modal-title">Registar nueva recompensa</h5>
                <button class="close" onclick="closeModal('modalRegistrarNuevoOficio')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyRegistrarNuevoOficio">
                <form id="formRegistrarNuevoOficio" action="{{ route('oficios.store') }}" method="POST">
                    @csrf

                    @php
                        $oficiosEliminadosDB = $oficiosEliminados;
                        $newCodigoOficio = $nuevoCodigoOficio;
                        $idOficioInput = 'idOficioInputRegistrar';
                        $idCodigoOficioInput = 'codigoOficioInputRegistrar';
                        $idGeneralMessageError = 'generalRegistrarOficioError';
                        $idDescripcionOficioInputRegistrar = 'descripcionOficioInputRegistrar';
                        $otherInputsArray = [$idDescripcionOficioInputRegistrar];
                        $searchDBField = 'idOficio';
                        $dbFieldsNameArray = ['nombre_Oficio', 'descripcion_Oficio'];
                    @endphp

                    <div class="form-group gap">
                        <label class="primary-label" id="codigoOficioLabel" for='{{ $idCodigoOficioInput }}'>Código de oficio:</label>
                        <input class="input-item readonly" id='{{ $idCodigoOficioInput }}' maxlength="13" value="{{ $newCodigoOficio }}" disabled>
                        <input type="text" id='{{ $idOficioInput }}' name="idOficio">
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label marginX" id="nameLabel"  for="nameInput">Nombre:</label>
                        <input class="input-item" type="text" id="nameInput" placeholder="Ingresar nombre" name="nombre_Oficio"
                                oninput="validateRealTimeInputLength(this, 60)">
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" id="descripcionLabel" for='{{ $idDescripcionOficioInputRegistrar }}'>Descripción:</label>
                        <textarea class="textarea normal" maxlength="100" id='{{ $idDescripcionOficioInputRegistrar }}' name="descripcion_Oficio" 
                                placeholder="Ingresar una breve descripción"></textarea>
                    </div>
                
                    <div class="form-group start">
                        <span class="inline-alert-message" id='{{ $idGeneralMessageError }}'> multiMessageError </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRegistrarNuevoOficio')">Cancelar</button>
                <button type="button" class="btn btn-primary" 
                        onclick="guardarModalRegistrarNuevoOficio('modalRegistrarNuevoOficio', 'formRegistrarNuevoOficio')">Guardar</button>
            </div>
        </div>
    </div>
</div>

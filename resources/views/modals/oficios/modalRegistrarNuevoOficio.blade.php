<div class="modal first"  id="modalRegistrarNuevoOficio">
    <div class="modal-dialog" id="modalRegistrarNuevoOficio-dialog">
        <div class="modal-content" id="modalRegistrarNuevoOficio-content">
            <div class="modal-header">
                <h5 class="modal-title">Registar nuevo oficio</h5>
                <button class="close noUserSelect" onclick="closeModal('modalRegistrarNuevoOficio')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyRegistrarNuevoOficio">
                <form id="formRegistrarNuevoOficio" action="{{ route('oficios.store') }}" method="POST">
                    @csrf

                    @php
                        $newIdOficio = $nuevoIdOficio;
                        $newCodigoOficio = $nuevoCodigoOficio;
                        $idCodigoOficioInput = 'codigoOficioInputRegistrar';
                        $idNombreOficioInput = 'nombreOficioInputRegistrar';
                        $idDescripcionOficioInputRegistrar = 'descripcionOficioInputRegistrar';
                        $idGeneralMessageError = 'generalRegistrarOficioError';
                        // $otherInputsArray = [$idDescripcionOficioInputRegistrar];
                        // $searchDBField = 'idOficio';
                        // $dbFieldsNameArray = ['nombre_Oficio', 'descripcion_Oficio'];
                    @endphp

                    <div class="form-group gap">
                        <label class="primary-label" id="codigoOficioLabel" for='{{ $idCodigoOficioInput }}'>Código de oficio:</label>
                        <input type="hidden" value="{{ $newIdOficio }}" name="idOficio" readonly>
                        <input class="input-item readonly" id='{{ $idCodigoOficioInput }}' maxlength="13" value="{{ $newCodigoOficio }}" disabled>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label" id="nameLabel" for='{{ $idNombreOficioInput }}'>Nombre:</label>
                        <input class="input-item" type="text" maxlength="60" id='{{ $idNombreOficioInput }}' placeholder="Ingresar nombre"
                                 name="nombre_Oficio">
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" id="descripcionLabel" for='{{ $idDescripcionOficioInputRegistrar }}'>Descripción (opcional):</label>
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

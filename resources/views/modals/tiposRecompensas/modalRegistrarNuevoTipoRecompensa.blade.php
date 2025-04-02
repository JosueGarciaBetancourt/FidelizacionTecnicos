<div class="modal first"  id="modalRegistrarNuevoTipoRecompensa">
    <div class="modal-dialog" id="modalRegistrarNuevoTipoRecompensa-dialog">
        <div class="modal-content" id="modalRegistrarNuevoTipoRecompensa-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear nuevo tipo de recompensa</h5>
                <button class="close noUserSelect" onclick="closeModal('modalRegistrarNuevoTipoRecompensa')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyRegistrarNuevoTipoRecompensa">
                <form id="formRegistrarNuevoTipoRecompensa" action="{{ route('tiposRecompensas.store') }}" method="POST">
                    @csrf
                    @php
                        $newIdTipoRecompensa = $nuevoIdTipoRecompensa;
                        $tiposRecompensasDB = $tiposRecompensas;
                        $newCodigoTipoRecompensa = $nuevoCodigoTipoRecompensa;
                        $idCodigoTipoRecompensaInput = 'codigoTipoRecompensaInputRegistrar';
                        $idNombreTipoRecompensaInput = 'nombreTipoRecompensaInputRegistrar';
                        $idDescripcionTipoRecompensaInputRegistrar = 'descripcionTipoRecompensaInputRegistrar';
                        $idColorTextoTipoRecompensaInput = 'colorTextoTipoRecompensaInputRegistrar';
                        $idColorFondoTipoRecompensaInput = 'colorFondoTipoRecompensaInputRegistrar';
                        $idPreviewColorSpan = 'previewColorTipoRecompensaSpanRegistrar';
                        $idGeneralMessageError = 'generalRegistrarTipoRecompensaError';
                        // $otherInputsArray = [$idDescripcionOficioInputRegistrar];
                        // $searchDBField = 'idOficio';
                        // $dbFieldsNameArray = ['nombre_Oficio', 'descripcion_Oficio'];
                    @endphp

                    <div class="form-group gap">
                        <label class="primary-label" id="codigoOficioLabel" for='{{ $idCodigoTipoRecompensaInput }}'>Código:</label>
                        <input type="hidden" value="{{ $newIdTipoRecompensa }}" name="idTipoRecompensa" readonly>
                        <input class="input-item readonly" id='{{ $idCodigoTipoRecompensaInput }}' maxlength="13" value="{{ $newCodigoTipoRecompensa }}" disabled>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label" id="nameLabel"  for='{{ $idNombreTipoRecompensaInput }}'>Nombre:</label>
                        <input class="input-item" type="text" maxlength="50" id='{{ $idNombreTipoRecompensaInput }}'
                            oninput="fillNamePreviewColorSpan(this, '{{ $idPreviewColorSpan }}')" placeholder="Ingresar tipo de recompensa" name="nombre_TipoRecompensa">
                    </div>
                    
                    <div class="form-group gap">
                        <label class="primary-label" id="descripcionLabel" for='{{ $idDescripcionTipoRecompensaInputRegistrar }}'>Descripción (opcional):</label>
                        <textarea class="textarea normal" maxlength="100" id='{{ $idDescripcionTipoRecompensaInputRegistrar }}' name="descripcion_TipoRecompensa" 
                            placeholder="Ingresar una breve descripción"></textarea>
                    </div>
                
                    <div class="form-group gap">
                        <div class="group-items">
                            <div class="form-group gap">
                                <label class="primary-label" for='{{ $idColorTextoTipoRecompensaInput }}'>Color de texto:</label>
                                <input type="color" class="colorPicker cursorPointer" id='{{ $idColorTextoTipoRecompensaInput }}' title="Seleccionar color"
                                    oninput="fillPreviewColorTextoSpan(this, '{{ $idPreviewColorSpan }}')" name="colorTexto_TipoRecompensa" value="#3206B0">
                            </div>
                            <div class="form-group colorFondoGap">
                                <label class="primary-label" for='{{ $idColorFondoTipoRecompensaInput }}'>Color de fondo:</label>
                                <input type="color" class="colorPicker cursorPointer" id='{{ $idColorFondoTipoRecompensaInput }}' title="Seleccionar color"
                                    oninput="fillPreviewColorFondoSpan(this, '{{ $idPreviewColorSpan }}')" name="colorFondo_TipoRecompensa" value="#DCD5F0">
                            </div>
                        </div>
                        
                        <div class="previewTipoRecompensaContainer">
                            <label class="primary-label noEditable">Previsualización:</label>
                            <span class="previewTipoRecompensa" id="{{ $idPreviewColorSpan }}" style="color:#3206B0; background-color: #DCD5F0;"></span> 
                        </div>
                    </div>

                    <div class="form-group start">
                        <span class="inline-alert-message" id='{{ $idGeneralMessageError }}'> multiMessageError </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRegistrarNuevoTipoRecompensa')">Cancelar</button>
                <button type="button" class="btn btn-primary" 
                        onclick="guardarmodalRegistrarNuevoTipoRecompensa('modalRegistrarNuevoTipoRecompensa', 'formRegistrarNuevoTipoRecompensa',
                                                                            {{ json_encode($tiposRecompensasDB) }})">Guardar</button>
            </div>
        </div>
    </div>
</div>

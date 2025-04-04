<div class="modal first"  id="modalRegistrarNuevoRango">
    <div class="modal-dialog" id="modalRegistrarNuevoRango-dialog">
        <div class="modal-content" id="modalRegistrarNuevoRango-content">
            <div class="modal-header">
                <h5 class="modal-title">Registar nuevo Rango</h5>
                <button class="close noUserSelect" onclick="closeModal('modalRegistrarNuevoRango')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyRegistrarNuevoRango">
                <form id="formRegistrarNuevoRango" action="{{ route('rangos.store') }}" method="POST">
                    @csrf

                    @php
                        $newIdRango = $nuevoIdRango;
                        $newCodigoRango = $nuevoCodigoRango;
                        $rangosDB = $rangos;
                        $idCodigoRangoInput = 'codigoRangoInputRegistrar';
                        $idColorTextoRangoInput = 'colorTextoRangoInputRegistrar';
                        $idColorFondoRangoInput = 'colorFondoRangoInputRegistrar';
                        $idNombreRangoInput = 'nombreRangoInputRegistrar';
                        $idDescripcionRangoInputRegistrar = 'descripcionRangoInputRegistrar';
                        $idPuntosMinimosInput = 'puntosMinimosRangoInputRegistrar';
                        $idGeneralMessageError = 'generalRegistrarRangoError';
                        $idPreviewColorSpan = 'previewColorSpanRegistrar';
                    @endphp

                    <div class="form-group gap">
                        <label class="primary-label" id="codigoRangoLabel" for='{{ $idCodigoRangoInput }}'>Código:</label>
                        <input type="hidden" value="{{ $newIdRango }}" name="idRango" readonly>
                        <input class="input-item readonly" id='{{ $idCodigoRangoInput }}' maxlength="6" value="{{ $newCodigoRango }}" disabled>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label" id="nameLabel" for='{{ $idNombreRangoInput }}'>Nombre:</label>
                        <input class="input-item" type="text" maxlength="35" id='{{ $idNombreRangoInput }}'
                            oninput="fillNamePreviewColorSpan(this, '{{ $idPreviewColorSpan }}')" placeholder="Ingresar nombre" name="nombre_Rango">
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" id="descripcionLabel" for='{{ $idDescripcionRangoInputRegistrar }}'>Descripción (opcional):</label>
                        <textarea class="textarea normal" maxlength="100" id='{{ $idDescripcionRangoInputRegistrar }}' name="descripcion_Rango" 
                                placeholder="Ingresar una breve descripción"></textarea>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label" id="puntosMinimosLabel"  for='{{ $idPuntosMinimosInput }}'>Puntos mínimos:</label>
                        <input class="input-item" type="number" oninput="validateRealTimeInputLength(this, 5), validateNumberRealTime(this)" 
                            id='{{ $idPuntosMinimosInput }}' placeholder="10000" name="puntosMinimos_Rango">
                    </div>

                    <div class="form-group gap">
                        <div class="group-items">
                            <div class="form-group gap">
                                <label class="primary-label" for='{{ $idColorTextoRangoInput }}'>Color de texto:</label>
                                <input type="color" class="colorPicker cursorPointer" id='{{ $idColorTextoRangoInput }}' title="Seleccionar color"
                                    oninput="fillPreviewColorTextoSpan(this, '{{ $idPreviewColorSpan }}')" name="colorTexto_Rango" value="#3206B0">
                            </div>
                            <div class="form-group colorFondoGap">
                                <label class="primary-label" for='{{ $idColorFondoRangoInput }}'>Color de fondo:</label>
                                <input type="color" class="colorPicker cursorPointer" id='{{ $idColorFondoRangoInput }}' title="Seleccionar color"
                                    oninput="fillPreviewColorFondoSpan(this, '{{ $idPreviewColorSpan }}')" name="colorFondo_Rango" value="#DCD5F0">
                            </div>
                        </div>
                        
                        <div class="previewRangoContainer">
                            <label class="primary-label noEditable">Previsualización:</label>
                            <span class="previewRango" id="{{ $idPreviewColorSpan }}" style="color:#3206B0; background-color: #DCD5F0;"></span> 
                        </div>
                    </div>
                    
                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'></span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRegistrarNuevoRango')">Cancelar</button>
                <button type="button" class="btn btn-primary" 
                        onclick="guardarModalRegistrarNuevoRango('modalRegistrarNuevoRango', 'formRegistrarNuevoRango',
                                                                {{ json_encode($rangosDB) }})">Guardar</button>
            </div>
        </div>
    </div>
</div>

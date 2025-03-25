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
                        $idCodigoRangoInput = 'codigoRangoInputRegistrar';
                        $idNombreRangoInput = 'nombreRangoInputRegistrar';
                        $idDescripcionRangoInputRegistrar = 'descripcionRangoInputRegistrar';
                        $idPuntosMinimosInput = 'puntosMinimosRangoInputRegistrar';
                        $idGeneralMessageError = 'generalRegistrarRangoError';
                        // $otherInputsArray = [$idDescripcionRangoInputRegistrar];
                        // $searchDBField = 'idRango';
                        // $dbFieldsNameArray = ['nombre_Rango', 'descripcion_Rango'];
                    @endphp

                    <div class="form-group gap">
                        <label class="primary-label" id="codigoRangoLabel" for='{{ $idCodigoRangoInput }}'>Código de rango:</label>
                        <input type="hidden" value="{{ $newIdRango }}" name="idRango" readonly>
                        <input class="input-item readonly" id='{{ $idCodigoRangoInput }}' maxlength="6" value="{{ $newCodigoRango }}" disabled>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label" id="nameLabel" for='{{ $idNombreRangoInput }}'>Nombre:</label>
                        <input class="input-item" type="text" maxlength="60" id='{{ $idNombreRangoInput }}' placeholder="Ingresar nombre"
                                 name="nombre_Rango">
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" id="descripcionLabel" for='{{ $idDescripcionRangoInputRegistrar }}'>Descripción (opcional):</label>
                        <textarea class="textarea normal" maxlength="100" id='{{ $idDescripcionRangoInputRegistrar }}' name="descripcion_Rango" 
                                placeholder="Ingresar una breve descripción"></textarea>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label marginX" id="puntosMinimosLabel"  for='{{ $idPuntosMinimosInput }}'>Puntos mínimos:</label>
                        <input class="input-item" type="number" oninput="validateRealTimeInputLength(this, 5), validateNumberRealTime(this)" 
                            id='{{ $idPuntosMinimosInput }}' placeholder="10000" name="puntosMinimos_Rango">
                    </div>

                    <div class="form-group start">
                        <span class="inline-alert-message" id='{{ $idGeneralMessageError }}'> multiMessageError </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRegistrarNuevoRango')">Cancelar</button>
                <button type="button" class="btn btn-primary" 
                        onclick="guardarModalRegistrarNuevoRango('modalRegistrarNuevoRango', 'formRegistrarNuevoRango')">Guardar</button>
            </div>
        </div>
    </div>
</div>

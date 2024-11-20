<div class="modal first"  id="modalRegistrarNuevoTipoRecompensa">
    <div class="modal-dialog" id="modalRegistrarNuevoTipoRecompensa-dialog">
        <div class="modal-content" id="modalRegistrarNuevoTipoRecompensa-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear nuevo tipo de recompensa</h5>
                <button class="close" onclick="closeModal('modalRegistrarNuevoTipoRecompensa')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyRegistrarNuevoTipoRecompensa">
                <form id="formRegistrarNuevoTipoRecompensa" action="{{ route('tiposRecompensas.store') }}" method="POST">
                    @csrf
                    @php
                        $tiposRecompensasDB = $tiposRecompensas;
                        $newCodigoTipoRecompensa = $idNuevoTipoRecompensa;
                        $idCodigoTipoRecompensaInput = 'codigoTipoRecompensaInputRegistrar';
                        $idNombreTipoRecompensaInput = 'nombreTipoRecompensaInputRegistrar';
                        $idGeneralMessageError = 'generalRegistrarTipoRecompensaError';
                        // $otherInputsArray = [$idDescripcionOficioInputRegistrar];
                        // $searchDBField = 'idOficio';
                        // $dbFieldsNameArray = ['nombre_Oficio', 'descripcion_Oficio'];
                    @endphp

                    <div class="form-group gap">
                        <label class="primary-label" id="codigoOficioLabel" for='{{ $idCodigoTipoRecompensaInput }}'>Código:</label>
                        <input class="input-item readonly" id='{{ $idCodigoTipoRecompensaInput }}' maxlength="13" value="{{ $newCodigoTipoRecompensa }}" disabled>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label" id="nameLabel"  for='{{ $idNombreTipoRecompensaInput }}'>Nombre:</label>
                        <input class="input-item" type="text" maxlength="60" id='{{ $idNombreTipoRecompensaInput }}' placeholder="Ingresar tipo de recompensa"
                                name="nombre_TipoRecompensa">
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

<div class="modal first"  id="modalEliminarTipoRecompensa">
    <div class="modal-dialog" id="modalEliminarTipoRecompensa-dialog">
        <div class="modal-content" id="modalEliminarTipoRecompensa-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar tipo de recompensa</h5>
                <button class="close" onclick="closeModal('modalEliminarTipoRecompensa')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEliminarTipoRecompensa">
                <form id="formEliminarTipoRecompensa" action="{{ route('tiposRecompensas.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <!-- Variables globales -->
                    @php
                        $tiposRecompensasDB = $tiposRecompensas;
                        $recompensasDB = $recompensas;
                        $idSearchMessageError = 'searchDeleteTipoRecompensaError';
                        $idCodigoTipoRecompensaInput = 'codigoTipoRecompensaInputDelete';
                        $idOptions = 'tipoRecompensaDeleteOptions';
                        $idNombreTipoRecompensaInput = 'nombreTipoRecompensaInputDelete';
                        $someHiddenIdInputsArray = ['idNumberTipoRecompensaDelete'];
                        $otherInputsArray = [$idNombreTipoRecompensaInput];
                        $idGeneralMessageError = 'generalDeleteTipoRecompensaError';
                        $searchDBField = 'idTipoRecompensa';
                        $dbFieldsNameArray = ['nombre_TipoRecompensa'];
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idTipoRecompensa">
                   
                    <div class="form-group start paddingY" id="idH5DeleteTipoRecompensaModalContainer">
                        <h5> *Solo puede eliminar un Tipo de Recompensa previamente creado que no tenga alguna recompensa asociada.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="tipoRecompensaEditSelect">Código:</label>
                        <div class="input-select" id="tipoRecompensaEditSelect">
                            <input class="input-select-item" type="text" id='{{ $idCodigoTipoRecompensaInput }}' maxlength="100" placeholder="Código" autocomplete="off"
                                oninput="filterOptions('{{ $idCodigoTipoRecompensaInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTimeIDInteger(this, '{{ $idOptions }}', '{{ $idSearchMessageError }}', 
                                        {{ json_encode($someHiddenIdInputsArray) }}, {{ json_encode($otherInputsArray) }}, 
                                        {{ json_encode($tiposRecompensasDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }})"
                                onclick="toggleOptions('{{ $idCodigoTipoRecompensaInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @foreach ($tiposRecompensasDB as $tipoRecompensa)
                                    @php
                                        $idNumberTipoRecompensa = htmlspecialchars($tipoRecompensa->idTipoRecompensa, ENT_QUOTES, 'UTF-8');
                                        $nombreTipoRecompensa = htmlspecialchars($tipoRecompensa->nombre_TipoRecompensa, ENT_QUOTES, 'UTF-8');
                                        $codigoTipoRecompensa = htmlspecialchars($tipoRecompensa->codigoTipoRecompensa, ENT_QUOTES, 'UTF-8');
                                        $value = $codigoTipoRecompensa;
                                    @endphp
                            
                                    <li onclick="selectOptionEliminarTipoRecompensa('{{ $value }}', '{{ $idNumberTipoRecompensa }}', '{{ $nombreTipoRecompensa }}', 
                                                '{{ $idCodigoTipoRecompensaInput }}', '{{ $idOptions }}', {{ json_encode($someHiddenIdInputsArray) }})">
                                        {{ $value }}
                                    </li>   
                                @endforeach
                            </ul>
                        </div>
                        <span class="noInline-alert-message" id='{{ $idSearchMessageError }}'>No se encontró el tipo de recompensa buscado</span>      
                    </div>
                   
                    <div class="form-group gap">
                        <label class="primary-label" id="nameLabel"  for='{{ $idNombreTipoRecompensaInput }}'>Nombre:</label>
                        <input class="input-item" type="text" maxlength="50" id='{{ $idNombreTipoRecompensaInput }}' placeholder="Ingresar tipo de recompensa"
                                disabled>
                    </div>
                    
                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'></span>      
                    </div>  
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEliminarTipoRecompensa')">Cancelar</button>
                <button type="button" class="btn btn-primary delete" 
                        onclick="guardarModalEliminarTipoRecompensa('modalEliminarTipoRecompensa', 'formEliminarTipoRecompensa', {{ json_encode($recompensasDB) }})">Eliminar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal first"  id="modalEditarTipoRecompensa">
    <div class="modal-dialog" id="modalEditarTipoRecompensa-dialog">
        <div class="modal-content" id="modalEditarTipoRecompensa-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar tipo de recompensa</h5>
                <button class="close" onclick="closeModal('modalEditarTipoRecompensa')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEditarTipoRecompensa">
                <form id="formEditarTipoRecompensa" action="{{ route('tiposRecompensas.update') }}" method="POST">
                    @method('PUT')
                    @csrf
                    <!-- Variables globales -->
                    @php
                        $tiposRecompensasDB = $tiposRecompensas;
                        $idSearchMessageError = 'searchEditTipoRecompensaError';
                        $idCodigoTipoRecompensaInput = 'codigoTipoRecompensaInputEdit';
                        $idOptions = 'tipoRecompensaEditOptions';
                        $idNombreTipoRecompensaInput = 'nombreTipoRecompensaInputEdit';
                        $someHiddenIdInputsArray = ['idNumberTipoRecompensaEdit'];
                        $otherInputsArray = [$idNombreTipoRecompensaInput];
                        $idGeneralMessageError = 'generalEditTipoRecompensaError';
                        $searchDBField = 'idTipoRecompensa';
                        $dbFieldsNameArray = ['nombre_TipoRecompensa'];
                    @endphp
                    <input type="text" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idTipoRecompensa">
                   
                    <div class="form-group start paddingY" id="idH5EditTipoRecompensaModalContainer">
                        <h5> *Solo puede editar el nombre de un Tipo de Recompensa previamente creado.</h5>
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
                            
                                    <li onclick="selectOptionEditTipoRecompensa('{{ $value }}', '{{ $idNumberTipoRecompensa }}', '{{ $nombreTipoRecompensa }}', 
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
                                name="nombre_TipoRecompensa">
                    </div>
                    
                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'></span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditarTipoRecompensa')">Cancelar</button>
                <button type="button" class="btn btn-primary update" 
                        onclick="guardarModalEditarTipoRecompensa('modalEditarTipoRecompensa', 'formEditarTipoRecompensa')">Actualizar</button>
            </div>
        </div>
    </div>
</div>


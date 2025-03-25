<div class="modal first"  id="modalInhabilitarTecnico">
    <div class="modal-dialog" id="modalInhabilitarTecnico-dialog">
        <div class="modal-content" id="modalInhabilitarTecnico-content">
            <div class="modal-header">
                <h5 class="modal-title">Inhabilitar técnico</h5>
                <button class="close noUserSelect" onclick="closeModal('modalInhabilitarTecnico')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyDisableTecnico">
                <form id="formInhabilitarTecnico" action="{{ route('tecnicos.disable') }}" method="POST">
                    @csrf
					@method('DELETE')
                    <!-- Variables globales -->
                    @php
                        $idsNombresOficiosBD = $idsNombresOficios;
                        $idInput = 'tecnicoDisableInput';
                        $idOptions = 'tecnicoDisableOptions';
                        $idMessageError = 'searchDisableTecnicoMessageError';
						$idModalDisableMessageError = 'disableTecnicoMessageError';
                        $someHiddenIdInputsArray = ['idDisableTecnicoInput'];
						$idCelularInput = 'celularInputDisable'; //El valor se debe modificar también en modalInhabilitarTecnico.js
                        $idFechaNacimientoInput = 'fechaNacimientoInputDisable';
                        $idOficioInputDisable = 'oficioInputDisable';
						$idPuntosActualesInput = 'puntosActualesInputDisable';
						$idHistoricoPuntosInput = 'historicoPuntosInputDisable';
						$idRangoInputDisable = 'rangoInputDisable';
                        $otherInputsArray = [$idCelularInput , $idOficioInputDisable, $idFechaNacimientoInput, $idPuntosActualesInput,
											$idHistoricoPuntosInput, $idRangoInputDisable];
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="8" name='idTecnico'>
                   
                    <div class="form-group start paddingY" id="idH5DisableTecnicoModalContainer">
                        <h5> Seleccione el técnico que desee inhabilitar.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="tecnicoDisableSelect">Tecnico:</label>
                        <div class="input-select" id="tecnicoDisableSelect">
                            <input class="input-select-item" type="text" id='{{ $idInput }}' maxlength="50" placeholder="DNI | Nombre" autocomplete="off"
                                oninput="validateValueOnRealTimeTecnicoDisable(this, '{{ $idMessageError }}', {{ json_encode($otherInputsArray) }}),
                                        filterOptionsTecnicoDisable(this, '{{ $idOptions }}')"
                                onclick="toggleOptionsTecnicoDisable('{{ $idInput }}', '{{ $idOptions }}')">
                            <ul class="select-items shortSteps" id="{{ $idOptions }}" onscroll="loadMoreOptionsTecnicoDisable(event)"></ul>
                        </div>
                        <span class="noInline-alert-message" id='{{ $idMessageError }}'>No se encontró el técnico buscado</span>    
                    </div>
                    
                    <div class="form-group gap">
                        <label class="primary-label noEditable" for="costoUnitarioInput">Celular:</label>
                        <input class="input-item" type="number" id='{{ $idCelularInput }}'
                                oninput="validateRealTimeInputLength(this, 9), validateNumberRealTime(this)" 
                                placeholder="987654321" disabled>
                    </div>

                    <div class="form-group-multiSelectDropdown">
                        <label class="primary-label noEditable multiSelectDrowpdownLabel" id="idOficioLabelInhabilitarTecnico">Oficio(s):</label>
                        <x-multiSelectDropdown
                            :idMultiSelectDropdownContainer="'idMultiSelectDropdownContainer_InhabilitarTecnico'"
                            :idInput="'multiSelectDropdownInput_InhabilitarTecnico'"
                            :idSelectedOptionsDiv="'multiSelectDropdownSelectedOptions_InhabilitarTecnico'"   
                            :options="$idsNombresOficiosBD"
                            :empyDataMessage="'No hay oficios registrados aún'"
                            :disabled="true"
                        />
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="idFechaNacimientoTecnicoLabel" for='{{ $idFechaNacimientoInput }}'>Fecha de nacimiento:</label>
                        <input class="input-item center" type="date" id='{{ $idFechaNacimientoInput }}' disabled>

                        <label class="primary-label noEditable" id="idPuntosActualesLabel" for='{{ $idPuntosActualesInput }}' >Puntos actuales:</label>
                        <input class="input-item center" id='{{ $idPuntosActualesInput }}' type="text"
                            placeholder="0" oninput="validateRealTimeInputLength(this, 4)" disabled>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="idHistoricoPuntosLabel"  for='{{ $idHistoricoPuntosInput }}'>Histórico de puntos:</label>
                        <input class="input-item center" id='{{ $idHistoricoPuntosInput }}' type="text" placeholder="0"
                            oninput="validateRealTimeInputLength(this, 6)" disabled>

                        <label class="primary-label noEditable" id="idRangoInputLabel"  for='{{ $idRangoInputDisable }}'>Rango:</label>
                        <input class="input-item center" id='{{ $idRangoInputDisable }}' type="text" placeholder="Plata, Oro ó Black"
                            oninput="validateRealTimeInputLength(this, 5)" disabled>
                    </div>

                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idModalDisableMessageError }}'>  </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer"  id="modalInhabilitarTecnico-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalInhabilitarTecnico')">Cancelar</button>
                <button type="button" class="btn btn-primary disable" 
                        onclick="guardarModalInhabilitarTecnico('modalInhabilitarTecnico', 'formInhabilitarTecnico')">Inhabilitar</button>
            </div>
        </div>
    </div>
</div>


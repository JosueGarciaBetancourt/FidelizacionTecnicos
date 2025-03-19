<div class="modal first"  id="modalEliminarTecnico">
    <div class="modal-dialog" id="modalEliminarTecnico-dialog">
        <div class="modal-content" id="modalEliminarTecnico-content">
            <div class="modal-header">
                <h5 class="modal-title">Inhabilitar técnico</h5>
                <button class="close noUserSelect" onclick="closeModal('modalEliminarTecnico')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyDeleteTecnico">
                <form id="formEliminarTecnico" action="{{ route('tecnicos.delete') }}" method="POST">
                    @csrf
					@method('DELETE')
                    <!-- Variables globales -->
                    @php
                        $idsNombresOficiosBD = $idsNombresOficios;
                        $idInput = 'tecnicoDeleteInput';
                        $idOptions = 'tecnicoDeleteOptions';
                        $idMessageError = 'searchDeleteTecnicoMessageError';
						$idModalDeleteMessageError = 'eliminarTecnicoMessageError';
                        $someHiddenIdInputsArray = ['idDeleteTecnicoInput'];
						$idCelularInput = 'celularInputDelete'; //El valor se debe modificar también en modalEliminarTecnico.js
                        $idFechaNacimientoInput = 'fechaNacimientoInputDelete';
                        $idOficioInputDelete = 'oficioInputDelete';
						$idPuntosActualesInput = 'puntosActualesInputDelete';
						$idHistoricoPuntosInput = 'historicoPuntosInputDelete';
						$idRangoInputDelete = 'rangoInputDelete';
                        $otherInputsArray = [$idCelularInput , $idOficioInputDelete, $idFechaNacimientoInput, $idPuntosActualesInput,
											$idHistoricoPuntosInput, $idRangoInputDelete];
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="8" name='idTecnico'>
                   
                    <div class="form-group start paddingY" id="idH5DeleteTecnicoModalContainer">
                        <h5> Seleccione el técnico que desee inhabilitar.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="tecnicoDeleteSelect">Tecnico:</label>
                        <div class="input-select" id="tecnicoDeleteSelect">
                            <input class="input-select-item" type="text" id='{{ $idInput }}' maxlength="50" placeholder="DNI | Nombre" autocomplete="off"
                                oninput="validateValueOnRealTimeTecnicoDelete(this, '{{ $idMessageError }}', {{ json_encode($otherInputsArray) }}),
                                        filterOptionsTecnicoDelete(this, '{{ $idOptions }}')"
                                onclick="toggleOptionsTecnicoDelete('{{ $idInput }}', '{{ $idOptions }}')">
                            <ul class="select-items shortSteps" id="{{ $idOptions }}" onscroll="loadMoreOptionsTecnicoDelete(event)"></ul>
                        </div>
                        <span class="noInline-alert-message" id='{{ $idMessageError }}'>No se encontró el técnico buscado</span>    
                    </div>
                    
                    <div class="form-group gap">
                        <label class="primary-label noEditable" for="costoUnitarioInput">Celular:</label>
                        <input class="input-item" type="number" id='{{ $idCelularInput }}'
                                oninput="validateRealTimeInputLength(this, 9), validateNumberRealTime(this)" 
                                placeholder="987654321" name="celularTecnico" disabled>
                    </div>

                    <div class="form-group-multiSelectDropdown">
                        <label class="primary-label noEditable multiSelectDrowpdownLabel" id="idOficioLabelEliminarTecnico">Oficio(s):</label>
                        <x-multiSelectDropdown
                            :idMultiSelectDropdownContainer="'idMultiSelectDropdownContainer_EliminarTecnico'"
                            :idInput="'multiSelectDropdownInput_EliminarTecnico'"
                            :idSelectedOptionsDiv="'multiSelectDropdownSelectedOptions_EliminarTecnico'"   
                            :options="$idsNombresOficiosBD"
                            :empyDataMessage="'No hay oficios registrados aún'"
                            :disabled="true"
                        />
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="idFechaNacimientoTecnicoLabel" for='{{ $idFechaNacimientoInput }}'>Fecha de nacimiento:</label>
                        <input class="input-item center" type="date" id='{{ $idFechaNacimientoInput }}'
                               name="fechaNacimiento_Tecnico" disabled>

                        <label class="primary-label noEditable" id="idPuntosActualesLabel"  for='{{ $idPuntosActualesInput }}' >Puntos actuales:</label>
                        <input class="input-item center" id='{{ $idPuntosActualesInput }}' type="text"
                                placeholder="0" name="totalPuntosActuales_Tecnico" oninput="validateRealTimeInputLength(this, 4)"
                                disabled>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="idHistoricoPuntosLabel"  for='{{ $idHistoricoPuntosInput }}'>Histórico de puntos:</label>
                        <input class="input-item center" id='{{ $idHistoricoPuntosInput }}' type="text" placeholder="0" name="historicoPuntos_Tecnico"
                                oninput="validateRealTimeInputLength(this, 6)" disabled>

                        <label class="primary-label noEditable" id="idRangoInputLabel"  for='{{ $idRangoInputDelete }}'>Rango:</label>
                        <input class="input-item center" id='{{ $idRangoInputDelete }}' type="text" placeholder="Plata, Oro ó Black"
                                oninput="validateRealTimeInputLength(this, 5)" disabled>
                    </div>

                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idModalDeleteMessageError }}'>  </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer"  id="modalEliminarTecnico-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEliminarTecnico')">Cancelar</button>
                <button type="button" class="btn btn-primary delete" 
                        onclick="guardarModalEliminarTecnico('modalEliminarTecnico', 'formEliminarTecnico')">Inhabilitar</button>
            </div>
        </div>
    </div>
</div>


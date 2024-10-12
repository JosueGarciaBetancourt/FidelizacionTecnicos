<div class="modal first"  id="modalEditarTecnico">
    <div class="modal-dialog" id="modalEditarTecnico-dialog">
        <div class="modal-content" id="modalEditarTecnico-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar técnico</h5>
                <button class="close" onclick="closeModal('modalEditarTecnico')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEditarTecnico">
                <form id="formEditarTecnico" action="{{ route('tecnicos.update') }}" method="POST">
                    @method('PUT')
                    @csrf
                    <!-- Variables globales -->
                    @php
                        $tecnicosDB = $tecnicos;
                        $dbFieldsNameArray = ['celularTecnico', 'oficioTecnico', 'fechaNacimiento_Tecnico', 
											'totalPuntosActuales_Tecnico', 'historicoPuntos_Tecnico', 'rangoTecnico'];
                        $idInput = 'tecnicoEditInput';
                        $idOptions = 'tecnicoEditOptions';
                        $idMessageError = 'searchEditTecnicoMessageError';
                        $someHiddenIdInputsArray = ['idEditTecnicoInput'];
						
						$idCelularInput = 'celularInputEdit'; //El valor se debe modificar también en modalEditarTecnico.js
                        $idFechaNacimientoInput = 'fechaNacimientoInputEdit';
                        $idOficioInputEdit = 'oficioInputEdit';
						$idPuntosActualesInput = 'puntosActualesInputEdit';
						$idHistoricoPuntosInput = 'historicoPuntosInputEdit';
						$idRangoInputEdit = 'rangoInputEdit';
                        $otherInputsArray = [$idCelularInput , $idOficioInputEdit, $idFechaNacimientoInput, $idPuntosActualesInput,
											$idHistoricoPuntosInput, $idRangoInputEdit];
                        $searchDBField = 'idTecnico';
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="8" name='{{ $searchDBField }}'>
                   
                    <div class="form-group start paddingY" id="idH5EditTecnicoModalContainer">
                        <h5> *Solo puede editar el celular y oficio de un técnico previamente creado.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="tecnicoEditSelect">Tecnico:</label>
                        <div class="input-select" id="tecnicoEditSelect">
                            <input class="input-select-item" type="text" id='{{ $idInput }}' maxlength="50" placeholder="DNI - Nombre"
                                oninput="filterOptions('{{ $idInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTime(this, '{{ $idOptions }}', '{{ $idMessageError }}', 
                                        {{ json_encode($someHiddenIdInputsArray) }}, {{ json_encode($otherInputsArray) }}, 
                                        {{ json_encode($tecnicosDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }})"

                                onclick="toggleOptions('{{ $idInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @foreach ($tecnicos as $tecnico)
                                    @php
                                        $idTecnico = htmlspecialchars($tecnico->idTecnico, ENT_QUOTES, 'UTF-8');
                                        $nombreTecnico = htmlspecialchars($tecnico->nombreTecnico, ENT_QUOTES, 'UTF-8');
                                        $celularTecnico = htmlspecialchars($tecnico->celularTecnico, ENT_QUOTES, 'UTF-8');
										$oficioTecnico = htmlspecialchars($tecnico->oficioTecnico, ENT_QUOTES, 'UTF-8');
										$fechaNacimiento_Tecnico = htmlspecialchars($tecnico->fechaNacimiento_Tecnico, ENT_QUOTES, 'UTF-8');
										$totalPuntosActuales_Tecnico = htmlspecialchars($tecnico->totalPuntosActuales_Tecnico, ENT_QUOTES, 'UTF-8');
										$historicoPuntos_Tecnico = htmlspecialchars($tecnico->historicoPuntos_Tecnico, ENT_QUOTES, 'UTF-8');
										$rangoTecnico = htmlspecialchars($tecnico->rangoTecnico, ENT_QUOTES, 'UTF-8');
                                        $value = $idTecnico . " - " . $nombreTecnico;
                                    @endphp
                            
                                   <li onclick="selectOptionEditarTecnico('{{ $value }}', '{{ $idTecnico }}', '{{ $nombreTecnico }}', '{{ $celularTecnico }}',
												'{{ $oficioTecnico }}', '{{ $fechaNacimiento_Tecnico }}', '{{ $totalPuntosActuales_Tecnico }}', 
                                                '{{ $historicoPuntos_Tecnico }}', '{{ $rangoTecnico }}', '{{ $idInput }}', '{{ $idOptions }}', 
                                                {{ json_encode($someHiddenIdInputsArray) }})">
                                        {{ $value }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <span class="noInline-alert-message" id='{{ $idMessageError }}'>No se encontró el técnico buscado</span>      
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="costoUnitarioInput">Celular:</label>
                        <div class="tooltip-container">
                            <span class="tooltip" id="idCelularTecnicoEditTooltip">Este es el mensaje del tooltip</span>
                        </div>
                        <input class="input-item" type="number" id='{{ $idCelularInput }}'
                                oninput="validateRealTimeInputLength(this, 9), validateNumberRealTime(this)" 
                                placeholder="987654321" name="celularTecnico">

                        <label class="primary-label" id='idOficioInputLabel' for='{{ $idOficioInputEdit }}'>Oficio:</label>
                        <x-onlySelect-input 
                            :idInput="$idOficioInputEdit"
                            :inputClassName="'onlySelectInput long'"
                            :placeholder="'Seleccionar oficio'"
                            :name="'oficioTecnico'"
                            :options="['Albañil', 'Enchapador', 'Enchapador/Albañil']"
                        />
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="idFechaNacimientoTecnicoLabel" for='{{ $idFechaNacimientoInput }}'>Fecha de nacimiento:</label>
                        <input class="input-item center" type="date" id='{{ $idFechaNacimientoInput }}'
                               name="fechaNacimiento_Tecnico" disabled>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="idPuntosActualesLabel"  for='{{ $idPuntosActualesInput }}' >Puntos actuales:</label>
                        <input class="input-item center" id='{{ $idPuntosActualesInput }}' type="text"
                               placeholder="0" name="totalPuntosActuales_Tecnico" oninput="validateRealTimeInputLength(this, 4)"
                               disabled>
                       
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="idHistoricoPuntosLabel"  for='{{ $idHistoricoPuntosInput }}'>Histórico de puntos:</label>
                        <input class="input-item center" id='{{ $idHistoricoPuntosInput }}' type="text" placeholder="0" name="historicoPuntos_Tecnico"
                                oninput="validateRealTimeInputLength(this, 6)" disabled>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="idRangoInputLabel"  for='{{ $idRangoInputEdit }}'>Rango:</label>
                        <input class="input-item center" id='{{ $idRangoInputEdit }}' type="text" placeholder="Plata, Oro ó Black" name="rangoTecnico"
                               oninput="validateRealTimeInputLength(this, 5)" disabled>
                    </div>

                    <div class="form-group start">
                        <span class="noInline-alert-message" id="editarTecnicoMessageError">  </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditarTecnico')">Cancelar</button>
                <button type="button" class="btn btn-primary update" 
                        onclick="guardarModalEditarTecnico('modalEditarTecnico', 'formEditarTecnico')">Actualizar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal first"  id="modalRecontratarTecnico">
    <div class="modal-dialog" id="modalRecontratarTecnico-dialog">
        <div class="modal-content" id="modalRecontratarTecnico-content">
            <div class="modal-header">
                <h5 class="modal-title">Habilitar técnico</h5>
                <button class="close noUserSelect" onclick="closeModal('modalRecontratarTecnico')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyRecontratarTecnico">
                <form id="formRecontratarTecnico" action="{{ route('tecnicos.rehire') }}" method="POST">
                    @csrf
                    <!-- Variables globales -->
                    @php
                        $tecnicosBorradosDB = $tecnicosBorrados;
                        $idsNombresOficiosBD = $idsNombresOficios;
                        $idInput = 'tecnicoRecontratarInput';
                        $idOptions = 'tecnicoRecontratarOptions';
                        $idMessageError = 'searchRecontratarTecnicoMessageError';
                        $someHiddenIdInputsArray = ['idRecontratarTecnicoInput', 'idsOficioRecontratarArrayInput'];
						$idCelularInput = 'celularInputRecontratar'; //El valor se debe modificar también en modalRecontratarTecnico.js
                        $idFechaNacimientoInput = 'fechaNacimientoInputRecontratar';
                        $idOficioInputRecontratar = 'oficioInputRecontratar';
						$idPuntosActualesInput = 'puntosActualesInputRecontratar';
						$idHistoricoPuntosInput = 'historicoPuntosInputRecontratar';
						$idRangoInputRecontratar = 'rangoInputRecontratar';
                        $otherInputsArray = [$idCelularInput , $idOficioInputRecontratar, $idFechaNacimientoInput, $idPuntosActualesInput,
											$idHistoricoPuntosInput, $idRangoInputRecontratar];
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="8" name='idTecnico'>
                   
                    <div class="form-group start paddingY" id="idH5RecontratarTecnicoModalContainer">
                        <h5> *Solo puede habilitar técnicos previamente inhabilitados.</h5>
                    </div>

                    {{--Optimizar este elemento, aplicar lazy load--}}
                    <div class="form-group gap">
                        <label class="primary-label" for="tecnicoRecontratarSelect">Tecnico:</label>
                        <div class="input-select" id="tecnicoRecontratarSelect">
                            <input class="input-select-item" type="text" id='{{ $idInput }}' maxlength="50" placeholder="DNI | Nombre" autocomplete="off"
                                oninput="filterOptions('{{ $idInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTimeTecnicoRecontratar(this, '{{ $idOptions }}', '{{ $idMessageError }}', 
                                        {{ json_encode($someHiddenIdInputsArray) }}, {{ json_encode($otherInputsArray) }}, {{ json_encode($tecnicosBorradosDB) }})"
                                onclick="toggleOptions('{{ $idInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @foreach ($tecnicosBorradosDB as $tecnico)
                                    @php
                                        $idTecnico = htmlspecialchars($tecnico->idTecnico, ENT_QUOTES, 'UTF-8');
                                        $nombreTecnico = htmlspecialchars($tecnico->nombreTecnico, ENT_QUOTES, 'UTF-8');
                                        $celularTecnico = htmlspecialchars($tecnico->celularTecnico, ENT_QUOTES, 'UTF-8');
										$idNameOficioTecnico = htmlspecialchars($tecnico->idNameOficioTecnico, ENT_QUOTES, 'UTF-8');
										$fechaNacimiento_Tecnico = htmlspecialchars($tecnico->fechaNacimiento_Tecnico, ENT_QUOTES, 'UTF-8');
										$totalPuntosActuales_Tecnico = htmlspecialchars($tecnico->totalPuntosActuales_Tecnico, ENT_QUOTES, 'UTF-8');
										$historicoPuntos_Tecnico = htmlspecialchars($tecnico->historicoPuntos_Tecnico, ENT_QUOTES, 'UTF-8');
										$rangoTecnico = htmlspecialchars($tecnico->rangoTecnico, ENT_QUOTES, 'UTF-8');
                                        $value = $idTecnico . " | " . $nombreTecnico;
                                    @endphp
                            
                                   <li onclick="selectOptionRecontratarTecnico('{{ $value }}', '{{ $idTecnico }}', '{{ $nombreTecnico }}', '{{ $celularTecnico }}',
												'{{ $idNameOficioTecnico }}', '{{ $fechaNacimiento_Tecnico }}', '{{ $totalPuntosActuales_Tecnico }}', 
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
                            <span class="tooltip" id="idCelularTecnicoRecontratarTooltip">Este es el mensaje del tooltip</span>
                        </div>
                        <input class="input-item" type="number" id='{{ $idCelularInput }}'
                                oninput="validateRealTimeInputLength(this, 9), validateNumberRealTime(this)" 
                                placeholder="987654321" name="celularTecnico">

                        <label class="primary-label" id='idOficioInputLabel' for='{{ $idOficioInputRecontratar }}'>Oficio:</label>
                        <x-onlySelect-input 
                            :idInput="$idOficioInputRecontratar"
                            :inputClassName="'onlySelectInput long'"
                            :placeholder="'Seleccionar oficio'"
                            :name="'oficioTecnico'"
                            :options="$idsNombresOficiosBD"
                            :onSelectFunction="'selectOptionRecontratarOficio'"
                            :onSpanClickFunction="'cleanHiddenOficiosRecontratarInput'"
                            :spanClassName="'noUserSelect'"
                        />
                        <input type="hidden" id='{{ $someHiddenIdInputsArray[1] }}' name="idOficioArray">
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="idFechaNacimientoTecnicoLabel" for='{{ $idFechaNacimientoInput }}'>Fecha de nacimiento:</label>
                        <input class="input-item center" type="date" id='{{ $idFechaNacimientoInput }}'
                               name="fechaNacimiento_Tecnico">

                        <label class="primary-label noEditable" id="idPuntosActualesLabel"  for='{{ $idPuntosActualesInput }}' >Puntos actuales:</label>
                        <input class="input-item center" id='{{ $idPuntosActualesInput }}' type="text"
                                placeholder="0" oninput="validateRealTimeInputLength(this, 4)">
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="idHistoricoPuntosLabel"  for='{{ $idHistoricoPuntosInput }}'>Histórico de puntos:</label>
                        <input class="input-item center" id='{{ $idHistoricoPuntosInput }}' type="text" placeholder="0"
                                oninput="validateRealTimeInputLength(this, 6)" disabled>

                        <label class="primary-label noEditable" id="idRangoInputLabel"  for='{{ $idRangoInputRecontratar }}'>Rango:</label>
                        <input class="input-item center" id='{{ $idRangoInputRecontratar }}' type="text" placeholder="Plata | Oro | Black"
                                oninput="validateRealTimeInputLength(this, 5)" disabled>
                    </div>

                    <div class="form-group start">
                        <span class="noInline-alert-message" id="RecontratarTecnicoMessageError">  </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRecontratarTecnico')">Cancelar</button>
                <button type="button" class="btn btn-primary recover" 
                        onclick="guardarModalRecontratarTecnico('modalRecontratarTecnico', 'formRecontratarTecnico')">Habilitar</button>
            </div>
        </div>
    </div>
</div>


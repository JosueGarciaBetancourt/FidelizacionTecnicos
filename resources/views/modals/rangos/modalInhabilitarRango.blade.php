<div class="modal first" id="modalInhabilitarRango">
    <div class="modal-dialog" id="modalInhabilitarRango-dialog">
        <div class="modal-content" id="modalInhabilitarRango-content">
            <div class="modal-header">
                <h5 class="modal-title">Inhabilitar Rango</h5>
                <button class="close noUserSelect" onclick="closeModal('modalInhabilitarRango')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyInhabilitarRango">
                <form id="formInhabilitarRango" action="{{ route('rangos.disable') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    @php
                        $rangosDB = $rangos->reject(fn($item) => $item->idRango === 1);
                        $idCodigoRangoInput = 'codigoRangoInputDisable';
                        $idOptions = 'rangoDisableOptions';
                        $idMessageError = 'searchDisableRangoError';
                        $idDescripcionRangoInputDisable = 'descripcionRangoInputDisable';
                        $idPuntosMinimosInput = 'puntosMinimosRangoInputDisable';
                        $someHiddenIdInputsArray = ['idRangoInputDisable'];
                        $otherInputsArray = [$idDescripcionRangoInputDisable, $idPuntosMinimosInput];
                        $idGeneralMessageError = 'generalDisableRangoError';
                        $searchDBField = 'idRango';
                        $dbFieldsNameArray = ['descripcion_Rango', 'puntosMinimos_Rango'];
                    @endphp

                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idRango">

                    <div class="form-group start paddingY" id="idH5DisableRangoModalContainer">
                        <h5>Seleccione el rango que desee inhabilitar.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="rangoDisableSelect">Rango:</label>
                        <div class="input-select" id="rangoDisableSelect">
                            <input class="input-select-item" type="text" id='{{ $idCodigoRangoInput }}'
                                maxlength="100" placeholder="Código | Descripción" autocomplete="off"
                                oninput="filterOptions('{{ $idCodigoRangoInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTimeIDInteger(this, '{{ $idOptions }}', '{{ $idMessageError }}', 
                                        {{ json_encode($someHiddenIdInputsArray) }}, {{ json_encode($otherInputsArray) }}, 
                                        {{ json_encode($rangosDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }},
                                        '{{ $idGeneralMessageError }}')"
                                onclick="toggleOptions('{{ $idCodigoRangoInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @if (count($rangosDB) > 0)
                                    @foreach ($rangosDB as $rango)
                                        @php
                                            $idNumberRango = htmlspecialchars($rango->idRango, ENT_QUOTES, 'UTF-8');
                                            $codigoRango = htmlspecialchars($rango->codigoRango, ENT_QUOTES, 'UTF-8');
                                            $nombreRango = htmlspecialchars($rango->nombre_Rango, ENT_QUOTES,'UTF-8');
                                            $descripcionRango = htmlspecialchars($rango->descripcion_Rango, ENT_QUOTES, 'UTF-8');
                                            $puntosMinimosRango = htmlspecialchars($rango->puntosMinimos_Rango, ENT_QUOTES,'UTF-8');
                                            $value = $codigoRango . ' | ' . $nombreRango;
                                        @endphp

                                        <li
                                            onclick="selectOptionInhabilitarRango('{{ $value }}', '{{ $idNumberRango }}', '{{ $descripcionRango }}',
                                                    '{{ $puntosMinimosRango }}', '{{ $idCodigoRangoInput }}', '{{ $idOptions }}',
                                                    {{ json_encode($someHiddenIdInputsArray) }})">
                                            {{ $value }}
                                        </li>
                                    @endforeach
                                @else
                                    <li>
                                        No hay rangos registrados aún
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <span class="noInline-alert-message" id='{{ $idMessageError }}'>No se encontró el rango buscado</span>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable"
                            for='{{ $idDescripcionRangoInputDisable }}'>Descripción:</label>
                        <textarea class="textarea normal" id='{{ $idDescripcionRangoInputDisable }}' placeholder="Breve descripción" disabled></textarea>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="puntosMinimosDisableLabel"  for='{{ $idPuntosMinimosInput }}'>Puntos mínimos:</label>
                        <input class="input-item" type="number" id='{{ $idPuntosMinimosInput }}' placeholder="10000" disabled>
                    </div>

                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'> </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="modalFooterInhabilitarRango">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('modalInhabilitarRango')">Cancelar</button>
                <button type="button" class="btn btn-primary disable"
                    onclick="guardarModalInhabilitarRango('modalInhabilitarRango', 'formInhabilitarRango')">Inhabilitar</button>
            </div>
        </div>
    </div>
</div>

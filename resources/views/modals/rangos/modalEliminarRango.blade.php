<div class="modal first"  id="modalEliminarRango">
    <div class="modal-dialog" id="modalEliminarRango-dialog">
        <div class="modal-content" id="modalEliminarRango-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Rango</h5>
                <button class="close noUserSelect" onclick="closeModal('modalEliminarRango')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEliminarRango">
                <form id="formEliminarRango" action="{{ route('rangos.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                  
                    @php
                        $rangosDB = $rangos->reject(fn($item) => $item->idRango === 1);
                        $idCodigoRangoInput = 'codigoRangoInputDelete';
                        $idColorTextoRangoInput = 'colorTextoRangoInputDelete';
                        $idColorFondoRangoInput = 'colorFondoRangoInputDelete';
                        $idOptions = 'rangoDeleteOptions';
                        $idMessageError = 'searchDeleteRangoError';
                        $idGeneralMessageError = 'generalDeleteRangoError';
                        $idDescripcionRangoInput = 'descripcionRangoInputDelete';
                        $idPuntosMinimosInput = 'puntosMinimosRangoInputDelete';
                        $someHiddenIdInputsArray = ['idNumberRangoInputDelete'];
                        $otherInputsArray = [$idDescripcionRangoInput, $idPuntosMinimosInput];
                        $searchDBField = 'idRango';
                        $dbFieldsNameArray = ['descripcion_Rango', 'puntosMinimos_Rango'];
                        $colorInputsArray = [$idColorTextoRangoInput, $idColorFondoRangoInput];
                        $dbColorFieldsNameArray = ['colorTexto_Rango', 'colorFondo_Rango'];
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idRango">
                   
                    <div class="form-group start paddingY" id="idH5DeleteRangoModalContainer">
                        <h5>Seleccione el rango que desee inhabilitar.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="rangoDeleteSelect">Rango:</label>
                        <div class="input-select" id="rangoDeleteSelect">
                            <input class="input-select-item" type="text" id='{{ $idCodigoRangoInput }}' maxlength="100" placeholder="Código | Descripción" autocomplete="off"
                                oninput="filterOptions('{{ $idCodigoRangoInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTimeRangoDelete(this, '{{ $idOptions }}', '{{ $idMessageError }}', 
                                        {{ json_encode($someHiddenIdInputsArray) }}, {{ json_encode($otherInputsArray) }}, {{ json_encode($colorInputsArray) }},
                                        {{ json_encode($rangosDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }}, {{ json_encode($dbColorFieldsNameArray) }},
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
                                            $colorTextoRango = htmlspecialchars($rango->colorTexto_Rango, ENT_QUOTES, 'UTF-8');
                                            $colorFondoRango = htmlspecialchars($rango->colorFondo_Rango, ENT_QUOTES, 'UTF-8');
                                            $value = $codigoRango . ' | ' . $nombreRango;
                                        @endphp

                                        <li onclick="selectOptionEliminarRango('{{ $value }}', '{{ $idNumberRango }}', '{{ $descripcionRango }}',
                                                    '{{ $puntosMinimosRango }}', '{{ $colorTextoRango }}', '{{ $colorFondoRango }}', 
                                                    '{{ $idCodigoRangoInput }}', '{{ $idOptions }}', {{ json_encode($someHiddenIdInputsArray) }})">
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
                        <label class="primary-label noEditable" for='{{ $idDescripcionRangoInput }}'>Descripción:</label>
                        <textarea class="textarea normal" id='{{ $idDescripcionRangoInput }}' placeholder="Breve descripción" disabled></textarea>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="puntosMinimosDeleteLabel"  for='{{ $idPuntosMinimosInput }}'>Puntos mínimos:</label>
                        <input class="input-item" type="number" id='{{ $idPuntosMinimosInput }}' placeholder="10000" disabled>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for='{{ $idColorTextoRangoInput }}'>Color de texto:</label>
                        <input type="color" class="colorPicker" id='{{ $idColorTextoRangoInput }}' title="Seleccionar color" value="#3206B0">
                        <label class="primary-label" for='{{ $idColorFondoRangoInput }}'>Color de fondo:</label>
                        <input type="color" class="colorPicker" id='{{ $idColorFondoRangoInput }}' title="Seleccionar color" value="#DCD5F0">
                    </div>

                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'> </span>      
                    </div>    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEliminarRango')">Cancelar</button>
                <button type="button" class="btn btn-primary delete" 
                        onclick="guardarModalEliminarRango('modalEliminarRango', 'formEliminarRango')">Eliminar</button>
            </div>
        </div>
    </div>
</div>


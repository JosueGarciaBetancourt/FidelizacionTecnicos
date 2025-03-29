<div class="modal first"  id="modalEditarRango">
    <div class="modal-dialog" id="modalEditarRango-dialog">
        <div class="modal-content" id="modalEditarRango-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar rango</h5>
                <button class="close noUserSelect" onclick="closeModal('modalEditarRango')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEditarRango">
                <form id="formEditarRango" action="{{ route('rangos.update') }}" method="POST">
                    @method('PUT')
                    @csrf
                    <!-- Variables globales -->
                    @php
                        $rangosDB = $rangos;
                        $idSearchMessageError = 'searchEditRangoError';
                        $idCodigoRangoInput = 'codigoRangoInputEdit';
                        $idColorTextoRangoInput = 'colorTextoRangoInputEdit';
                        $idColorFondoRangoInput = 'colorFondoRangoInputEdit';
                        $idOptions = 'rangoEditOptions';
                        $idDescripcionRangoInput = 'descripcionRangoInputEdit';
                        $idPuntosMinimosInput = 'puntosMinimosRangoInputEdit';
                        $someHiddenIdInputsArray = ['idNumberRango'];
                        $otherInputsArray = [$idDescripcionRangoInput, $idPuntosMinimosInput, $idColorTextoRangoInput, $idColorFondoRangoInput];
                        $idGeneralMessageError = 'generalEditRangoError';
                        $searchDBField = 'idRango';
                        $dbFieldsNameArray = ['descripcion_Rango', 'puntosMinimos_Rango', 'colorTexto_Rango', 'colorFondo_Rango'];
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idRango">
                   
                    {{-- <div class="form-group start paddingY" id="idH5EditRangoModalContainer">
                        <h5> *Solo puede editar la descripción de un rango previamente creado.</h5>
                    </div> --}}

                    <div class="form-group gap">
                        <label class="primary-label" for="rangoEditSelect">Rango:</label>
                        <div class="input-select" id="rangoEditSelect">
                            <input class="input-select-item" type="text" id='{{ $idCodigoRangoInput }}' maxlength="100" placeholder="Código - Nombre" autocomplete="off"
                                oninput="filterOptions('{{ $idCodigoRangoInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTimeRangoEdit(this, '{{ $idOptions }}', '{{ $idSearchMessageError }}', 
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
                                            $nombreRango = htmlspecialchars($rango->nombre_Rango, ENT_QUOTES, 'UTF-8');
                                            $descripcionRango = htmlspecialchars($rango->descripcion_Rango, ENT_QUOTES, 'UTF-8');
                                            $puntosMinimosRango = htmlspecialchars($rango->puntosMinimos_Rango, ENT_QUOTES, 'UTF-8');
                                            $colorTextoRango = htmlspecialchars($rango->colorTexto_Rango, ENT_QUOTES, 'UTF-8');
                                            $colorFondoRango = htmlspecialchars($rango->colorFondo_Rango, ENT_QUOTES, 'UTF-8');
                                            $value = $codigoRango . " | " . $nombreRango;
                                        @endphp
                                
                                        <li onclick="selectOptionEditRango('{{ $value }}', '{{ $idNumberRango }}', '{{ $descripcionRango }}',
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
                        <span class="noInline-alert-message" id='{{ $idSearchMessageError }}'>No se encontró el rango buscado</span>      
                    </div>
                   
                    <div class="form-group gap">
                        <label class="primary-label" for='{{ $idDescripcionRangoInput }}'>Descripción:</label>
                        <textarea class="textarea normal" id='{{ $idDescripcionRangoInput }}' name="descripcion_Rango" 
                                  placeholder="Breve descripción"></textarea>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" id="puntosMinimosLabel"  for='{{ $idPuntosMinimosInput }}'>Puntos mínimos:</label>
                        <input class="input-item" type="number" id='{{ $idPuntosMinimosInput }}' oninput="validateRealTimeInputLength(this, 5),
                            validateNumberRealTime(this)" placeholder="10000" name="puntosMinimos_Rango">
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for='{{ $idColorTextoRangoInput }}'>Color de texto:</label>
                        <input type="color" class="colorPicker" id='{{ $idColorTextoRangoInput }}' title="Seleccionar color"
                            name="colorTexto_Rango" value="#3206B0">
                        <label class="primary-label" for='{{ $idColorFondoRangoInput }}'>Color de fondo:</label>
                        <input type="color" class="colorPicker" id='{{ $idColorFondoRangoInput }}' title="Seleccionar color"
                            name="colorFondo_Rango" value="#DCD5F0">
                    </div>
                    
                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'>  </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditarRango')">Cancelar</button>
                <button type="button" class="btn btn-primary update" 
                        onclick="guardarModalEditarRango('modalEditarRango', 'formEditarRango', {{ json_encode($rangosDB) }})">Actualizar</button>
            </div>
        </div>
    </div>
</div>


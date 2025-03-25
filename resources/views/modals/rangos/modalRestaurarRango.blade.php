<div class="modal first"  id="modalRestaurarRango">
    <div class="modal-dialog" id="modalRestaurarRango-dialog">
        <div class="modal-content" id="modalRestaurarRango-content">
            <div class="modal-header">
                <h5 class="modal-title">Habilitar rango</h5>
                <button class="close noUserSelect" onclick="closeModal('modalRestaurarRango')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyRestaurarRango">
                <form id="formRestaurarRango" action="{{ route('rangos.restore') }}" method="POST">
                    @csrf
                    <!-- Variables globales -->
                    @php
                        $rangosEliminadosDB = $rangosEliminados;
                        $codigoInputRangoRestaurar = 'codigoRangoInputRestaurar';
                        $idOptions = 'RangoRestaurarOptions';
                        $idMessageError = 'searchRestaurarRangoError';
                        $idGeneralMessageError = 'generalRestaurarRangoError';
                        $idDescripcionRangoInputRestaurar = 'descripcionRangoInputRestaurar';
                        $idPuntosMinimosInput = 'puntosMinimosRangoInputRestaurar';
                        $someHiddencodigoInputRangoRestaurarsArray = ['idNumberRangoInputRestaurar'];
                        $otherInputsArray = [$idDescripcionRangoInputRestaurar, $idPuntosMinimosInput];
                        $searchDBField = 'idRango';
                        $dbFieldsNameArray = ['descripcion_Rango', 'puntosMinimos_Rango'];
                    @endphp

                    <input type="hidden" id='{{ $someHiddencodigoInputRangoRestaurarsArray[0] }}' maxlength="9" name="idRango">
                   
                    <div class="form-group start paddingY" id="idH5RestaurarRangoModalContainer">
                        <h5>*Solo puede habilitar rangos previamente inhabilitados.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="rangoRestaurarSelect">Rango:</label>
                        <div class="input-select" id="rangoRestaurarSelect">
                            <input class="input-select-item" type="text" id='{{ $codigoInputRangoRestaurar }}' maxlength="100" placeholder="Código | Nombre" autocomplete="off"
                                oninput="filterOptions('{{ $codigoInputRangoRestaurar }}', '{{ $idOptions }}'),
                                        validateValueOnRealTimeIDInteger(this, '{{ $idOptions }}', '{{ $idMessageError }}', 
                                        {{ json_encode($someHiddencodigoInputRangoRestaurarsArray) }}, {{ json_encode($otherInputsArray) }}, 
                                        {{ json_encode($rangosEliminadosDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }},
                                        '{{ $idGeneralMessageError }}')"
                                onclick="toggleOptions('{{ $codigoInputRangoRestaurar }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @if (count($rangosEliminadosDB) > 0)
                                @foreach ($rangosEliminadosDB as $rango)
                                    @php
                                        $idNumberRango = htmlspecialchars($rango->idRango, ENT_QUOTES, 'UTF-8');
                                        $codigoRango = htmlspecialchars($rango->codigoRango, ENT_QUOTES, 'UTF-8');
                                        $nombreRango = htmlspecialchars($rango->nombre_Rango, ENT_QUOTES, 'UTF-8');
                                        $descripcionRango = htmlspecialchars($rango->descripcion_Rango, ENT_QUOTES, 'UTF-8');
                                        $puntosMinimosRango = htmlspecialchars($rango->puntosMinimos_Rango, ENT_QUOTES,'UTF-8');
                                        $value = $codigoRango . " | " . $nombreRango;
                                    @endphp
                            
                                    <li onclick="selectOptionRestaurarRango('{{ $value }}', '{{ $idNumberRango }}', '{{ $descripcionRango }}', 
                                                '{{ $puntosMinimosRango }}', '{{ $codigoInputRangoRestaurar }}', '{{ $idOptions }}', 
                                                {{ json_encode($someHiddencodigoInputRangoRestaurarsArray) }})">
                                        {{ $value }}
                                    </li>
                                @endforeach
                                @else
                                    <li>
                                        No hay rangos inhabilitados aún
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <span class="noInline-alert-message" id='{{ $idMessageError }}'>No se encontró el rango buscado</span>      
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" for='{{ $idDescripcionRangoInputRestaurar }}'>Descripción:</label>
                        <textarea class="textarea normal" id='{{ $idDescripcionRangoInputRestaurar }}' placeholder="Breve descripción" disabled></textarea>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="puntosMinimosDisableLabel"  for='{{ $idPuntosMinimosInput }}'>Puntos mínimos:</label>
                        <input class="input-item" type="number" id='{{ $idPuntosMinimosInput }}' placeholder="10000" disabled>
                    </div>

                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'>  </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRestaurarRango')">Cancelar</button>
                <button type="button" class="btn btn-primary recover" 
                        onclick="guardarModalRestaurarRango('modalRestaurarRango', 'formRestaurarRango')">Habilitar</button>
            </div>
        </div>
    </div>
</div>


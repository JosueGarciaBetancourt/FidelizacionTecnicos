<div class="modal first"  id="modalInhabilitarTipoRecompensa">
    <div class="modal-dialog" id="modalInhabilitarTipoRecompensa-dialog">
        <div class="modal-content" id="modalInhabilitarTipoRecompensa-content">
            <div class="modal-header">
                <h5 class="modal-title">Inhabilitar tipo de recompensa</h5>
                <button class="close noUserSelect" onclick="closeModal('modalInhabilitarTipoRecompensa')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEliminarTipoRecompensa">
                <form id="formInhabilitarTipoRecompensa" action="{{ route('tiposRecompensas.disable') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    @php
                        $tiposRecompensasDB = $tiposRecompensas;
                        $recompensasDB = $recompensas;
                        $idSearchMessageError = 'searchDisableTipoRecompensaError';
                        $idCodigoTipoRecompensaInput = 'codigoTipoRecompensaInputDisable';
                        $idOptions = 'tipoRecompensaDisableOptions';
                        $idNombreTipoRecompensaInput = 'nombreTipoRecompensaInputDisable';
                        $idDescripcionTipoRecompensaInput = 'descripcionTipoRecompensaInputDisable';
                        $idColorTextoTipoRecompensaInput = 'colorTextoTipoRecompensaInputDisable';
                        $idColorFondoTipoRecompensaInput = 'colorFondoTipoRecompensaInputDisable';
                        $someHiddenIdInputsArray = ['idNumberTipoRecompensaDisable'];
                        $otherInputsArray = [$idNombreTipoRecompensaInput, $idDescripcionTipoRecompensaInput];
                        $idGeneralMessageError = 'generalDisableTipoRecompensaError';
                        $searchDBField = 'idTipoRecompensa';
                        $dbFieldsNameArray = ['nombre_TipoRecompensa', 'descripcion_TipoRecompensa'];
                        $colorInputsArray = [$idColorTextoTipoRecompensaInput, $idColorFondoTipoRecompensaInput];
                        $dbColorFieldsNameArray = ['colorTexto_TipoRecompensa', 'colorFondo_TipoRecompensa'];
                        $idPreviewColorSpan = 'previewColorSpanTipoRecompensaDisable';
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idTipoRecompensa">
                   
                    <div class="form-group start paddingY" id="idH5DisableTipoRecompensaModalContainer">
                        <h5> *Solo puede inhabilitar un Tipo de Recompensa previamente creado que no tenga alguna recompensa asociada.</h5>
                    </div>

                    <div class="form-group gap" id="form-group-CodigoTipoRecompensaDisable">
                        <label class="primary-label" for="tipoRecompensaDisableSelect">Código:</label>
                        <div class="input-select" id="tipoRecompensaDisableSelect">
                            <input class="input-select-item" type="text" id='{{ $idCodigoTipoRecompensaInput }}' maxlength="100" placeholder="Código" autocomplete="off"
                                oninput="filterOptions('{{ $idCodigoTipoRecompensaInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTimeTipoRecompensaDisable(this, '{{ $idOptions }}', '{{ $idSearchMessageError }}', 
                                        {{ json_encode($someHiddenIdInputsArray) }}, {{ json_encode($otherInputsArray) }}, {{ json_encode($colorInputsArray) }},
                                        {{ json_encode($tiposRecompensasDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }}, 
                                        {{ json_encode($dbColorFieldsNameArray) }}, '{{ $idGeneralMessageError }}')"
                                onclick="toggleOptions('{{ $idCodigoTipoRecompensaInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @if (count($tiposRecompensasDB) > 0)
                                    @foreach ($tiposRecompensasDB as $tipoRecompensa)
                                        @php
                                            $idNumberTipoRecompensa = htmlspecialchars($tipoRecompensa->idTipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $codigoTipoRecompensa = htmlspecialchars($tipoRecompensa->codigoTipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $nombreTipoRecompensa = htmlspecialchars($tipoRecompensa->nombre_TipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $descripcionTipoRecompensa = htmlspecialchars($tipoRecompensa->descripcion_TipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $colorTextoTipoRecompensa = htmlspecialchars($tipoRecompensa->colorTexto_TipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $colorFondoTipoRecompensa = htmlspecialchars($tipoRecompensa->colorFondo_TipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $value = $codigoTipoRecompensa;
                                        @endphp
                                
                                        <li onclick="selectOptionInhabilitarTipoRecompensa('{{ $value }}', '{{ $idNumberTipoRecompensa }}', '{{ $nombreTipoRecompensa }}',
                                            '{{ $descripcionTipoRecompensa }}', '{{ $colorTextoTipoRecompensa }}', '{{ $colorFondoTipoRecompensa }}', 
                                            '{{ $idCodigoTipoRecompensaInput }}', '{{ $idOptions }}', {{ json_encode($someHiddenIdInputsArray) }})">
                                            {{ $value }}
                                        </li>   
                                    @endforeach
                                @else
                                    <li>
                                        No hay tipos de recompensas registrados aún
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <span class="noInline-alert-message" id='{{ $idSearchMessageError }}'>No se encontró el tipo de recompensa buscado</span>      
                    </div>
                   
                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="nameLabel"  for='{{ $idNombreTipoRecompensaInput }}'>Nombre:</label>
                        <input class="input-item" type="text" maxlength="50" id='{{ $idNombreTipoRecompensaInput }}' placeholder="Ingresar tipo de recompensa"
                                disabled>
                    </div>
                    
                    <div class="form-group gap">
                        <label class="primary-label noEditable" for='{{ $idDescripcionTipoRecompensaInput }}'>Descripción:</label>
                        <textarea class="textarea normal" id='{{ $idDescripcionTipoRecompensaInput }}'
                                placeholder="Breve descripción" disabled></textarea>
                    </div>

                    <div class="form-group gap">
                        <div class="group-items">
                            <div class="form-group gap">
                                <label class="primary-label noEditable" for='{{ $idColorTextoTipoRecompensaInput }}'>Color de texto:</label>
                                <input type="color" class="colorPicker" id='{{ $idColorTextoTipoRecompensaInput }}' title="Seleccionar color"
                                    oninput="fillPreviewColorTextoSpan(this, '{{ $idPreviewColorSpan }}')"
                                    value="#3206B0" disabled>
                            </div>
                            <div class="form-group colorFondoGap">
                                <label class="primary-label noEditable" for='{{ $idColorFondoTipoRecompensaInput }}'>Color de fondo:</label>
                                <input type="color" class="colorPicker" id='{{ $idColorFondoTipoRecompensaInput }}' title="Seleccionar color"
                                    oninput="fillPreviewColorFondoSpan(this, '{{ $idPreviewColorSpan }}')" 
                                    value="#DCD5F0" disabled>
                            </div>
                        </div>
                        
                        <div class="previewTipoRecompensaContainer">
                            <label class="primary-label noEditable">Previsualización:</label>
                            <span class="previewTipoRecompensa" id="{{ $idPreviewColorSpan }}" style="color:#3206B0; background-color: #DCD5F0;" disabled></span> 
                        </div>
                    </div>

                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'></span>      
                    </div>  
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalInhabilitarTipoRecompensa')">Cancelar</button>
                <button type="button" class="btn btn-primary disable" 
                        onclick="guardarModalInhabilitarTipoRecompensa('modalInhabilitarTipoRecompensa', 'formInhabilitarTipoRecompensa', {{ json_encode($recompensasDB) }})">Inhabilitar</button>
            </div>
        </div>
    </div>
</div>


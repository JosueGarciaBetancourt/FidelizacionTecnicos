<div class="modal first"  id="modalRestaurarTipoRecompensa">
    <div class="modal-dialog" id="modalRestaurarTipoRecompensa-dialog">
        <div class="modal-content" id="modalRestaurarTipoRecompensa-content">
            <div class="modal-header">
                <h5 class="modal-title">Restaurar tipo de recompensa</h5>
                <button class="close noUserSelect" onclick="closeModal('modalRestaurarTipoRecompensa')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyRestaurarTipoRecompensa">
                <form id="formRestaurarTipoRecompensa" action="{{ route('tiposRecompensas.restore') }}" method="POST">
                    @csrf

                    @php
                        $tiposRecompensasDB = $tiposRecompensasEliminados;
                        $recompensasDB = $recompensas;
                        $idSearchMessageError = 'searchRestoreTipoRecompensaError';
                        $idCodigoTipoRecompensaInput = 'codigoTipoRecompensaInputRestore';
                        $idOptions = 'tipoRecompensaRestoreOptions';
                        $idNombreTipoRecompensaInput = 'nombreTipoRecompensaInputRestore';
                        $idDescripcionTipoRecompensaInput = 'descripcionTipoRecompensaInputRestore';
                        $idColorTextoTipoRecompensaInput = 'colorTextoTipoRecompensaInputRestore';
                        $idColorFondoTipoRecompensaInput = 'colorFondoTipoRecompensaInputRestore';
                        $someHiddenIdInputsArray = ['idNumberTipoRecompensaRestore'];
                        $otherInputsArray = [$idNombreTipoRecompensaInput, $idDescripcionTipoRecompensaInput];
                        $idGeneralMessageError = 'generalRestoreTipoRecompensaError';
                        $searchDBField = 'idTipoRecompensa';
                        $dbFieldsNameArray = ['nombre_TipoRecompensa', 'descripcion_TipoRecompensa'];
                        $colorInputsArray = [$idColorTextoTipoRecompensaInput, $idColorFondoTipoRecompensaInput];
                        $dbColorFieldsNameArray = ['colorTexto_TipoRecompensa', 'colorFondo_TipoRecompensa'];
                        $idPreviewColorSpan = 'previewColorSpanTipoRecompensaRestore';
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idTipoRecompensa">
                   
                    <div class="form-group start paddingY" id="idH5RestoreTipoRecompensaModalContainer">
                        <h5>*Solo puede habilitar tipos de recompensas previamente inhabilitados.</h5>
                    </div>

                    <div class="form-group gap" id="form-group-CodigoTipoRecompensaRestore">
                        <label class="primary-label" for="tipoRecompensaRestoreSelect">Código:</label>
                        <div class="input-select" id="tipoRecompensaRestoreSelect">
                            <input class="input-select-item" type="text" id='{{ $idCodigoTipoRecompensaInput }}' maxlength="100" placeholder="Código" autocomplete="off"
                                oninput="filterOptions('{{ $idCodigoTipoRecompensaInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTimeTipoRecompensaRestore(this, '{{ $idOptions }}', '{{ $idSearchMessageError }}', 
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
                                
                                        <li onclick="selectOptionRestaurarTipoRecompensa('{{ $value }}', '{{ $idNumberTipoRecompensa }}', '{{ $nombreTipoRecompensa }}',
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
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRestaurarTipoRecompensa')">Cancelar</button>
                <button type="button" class="btn btn-primary recover" 
                        onclick="guardarModalRestaurarTipoRecompensa('modalRestaurarTipoRecompensa', 'formRestaurarTipoRecompensa', 
                                {{ json_encode($recompensasDB) }})">Habilitar</button>
            </div>
        </div>
    </div>
</div>


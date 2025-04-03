<div class="modal first"  id="modalEditarTipoRecompensa">
    <div class="modal-dialog" id="modalEditarTipoRecompensa-dialog">
        <div class="modal-content" id="modalEditarTipoRecompensa-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar tipo de recompensa</h5>
                <button class="close noUserSelect" onclick="closeModal('modalEditarTipoRecompensa')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEditarTipoRecompensa">
                <form id="formEditarTipoRecompensa" action="{{ route('tiposRecompensas.update') }}" method="POST">
                    @method('PUT')
                    @csrf
                    <!-- Variables globales -->
                    @php
                        $tiposRecompensasDB = $tiposRecompensas;
                        $recompensasDB = $recompensas;
                        $idSearchMessageError = 'searchEditTipoRecompensaError';
                        $idCodigoTipoRecompensaInput = 'codigoTipoRecompensaInputEdit';
                        $idOptions = 'tipoRecompensaEditOptions';
                        $idNombreTipoRecompensaInput = 'nombreTipoRecompensaInputEdit';
                        $idDescripcionTipoRecompensaInput = 'descripcionTipoRecompensaInputEdit';
                        $idColorTextoTipoRecompensaInput = 'colorTextoTipoRecompensaInputEdit';
                        $idColorFondoTipoRecompensaInput = 'colorFondoTipoRecompensaInputEdit';
                        $someHiddenIdInputsArray = ['idNumberTipoRecompensaEdit'];
                        $otherInputsArray = [$idNombreTipoRecompensaInput, $idDescripcionTipoRecompensaInput];
                        $idGeneralMessageError = 'generalEditTipoRecompensaError';
                        $searchDBField = 'idTipoRecompensa';
                        $dbFieldsNameArray = ['nombre_TipoRecompensa', 'descripcion_TipoRecompensa'];
                        $colorInputsArray = [$idColorTextoTipoRecompensaInput, $idColorFondoTipoRecompensaInput];
                        $dbColorFieldsNameArray = ['colorTexto_TipoRecompensa', 'colorFondo_TipoRecompensa'];
                        $idPreviewColorSpan = 'previewColorSpanTipoRecompensaEdit';
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idTipoRecompensa">
                   
                    <div class="form-group start paddingY" id="idH5EditTipoRecompensaModalContainer">
                        <h5> *Solo puede editar el nombre de un Tipo de Recompensa previamente creado que no tenga alguna recompensa asociada.</h5>
                    </div>

                    <div class="form-group gap" id="form-group-CodigoTipoRecompensa">
                        <label class="primary-label" for="tipoRecompensaEditSelect">Código:</label>
                        <div class="input-select" id="tipoRecompensaEditSelect">
                            <input class="input-select-item" type="text" id='{{ $idCodigoTipoRecompensaInput }}' maxlength="100" placeholder="Código" autocomplete="off"
                                oninput="filterOptions('{{ $idCodigoTipoRecompensaInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTimeTipoRecompensaEdit(this, '{{ $idOptions }}', '{{ $idSearchMessageError }}', 
                                        {{ json_encode($someHiddenIdInputsArray) }}, {{ json_encode($otherInputsArray) }}, {{ json_encode($colorInputsArray) }}, 
                                        {{ json_encode($tiposRecompensasDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }}, {{ json_encode($dbColorFieldsNameArray) }},
                                        '{{ $idGeneralMessageError }}')"
                                onclick="toggleOptions('{{ $idCodigoTipoRecompensaInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @if (count($tiposRecompensasDB) > 0)
                                    @foreach ($tiposRecompensasDB as $tipoRecompensa)
                                        @php
                                            $idNumberTipoRecompensa = htmlspecialchars($tipoRecompensa->idTipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $codigoTipoRecompensa = htmlspecialchars($tipoRecompensa->codigoTipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $nombreTipoRecompensa = htmlspecialchars($tipoRecompensa->nombre_TipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $descripcionTipoRecompensa = htmlspecialchars($tipoRecompensa->descripcion_TipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $colorTextoRango = htmlspecialchars($tipoRecompensa->colorTexto_TipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $colorFondoRango = htmlspecialchars($tipoRecompensa->colorFondo_TipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $value = $codigoTipoRecompensa;
                                        @endphp
                                
                                        <li onclick="selectOptionEditTipoRecompensa('{{ $value }}', '{{ $idNumberTipoRecompensa }}', '{{ $nombreTipoRecompensa }}', 
                                                    '{{ $descripcionTipoRecompensa }}', '{{ $colorTextoRango }}',  '{{ $colorFondoRango }}',
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
                        <label class="primary-label" id="nameLabel" for='{{ $idNombreTipoRecompensaInput }}'>Nombre:</label>
                        <input class="input-item" type="text" maxlength="50" id='{{ $idNombreTipoRecompensaInput }}' placeholder="Ingresar tipo de recompensa"
                                oninput="fillNamePreviewColorSpan(this, '{{ $idPreviewColorSpan }}')" name="nombre_TipoRecompensa">
                    </div>
                    
                    <div class="form-group gap">
                        <label class="primary-label" for='{{ $idDescripcionTipoRecompensaInput }}'>Descripción:</label>
                        <textarea class="textarea normal" id='{{ $idDescripcionTipoRecompensaInput }}' name="descripcion_TipoRecompensa" 
                                placeholder="Breve descripción"></textarea>
                    </div>

                    <div class="form-group gap">
                        <div class="group-items">
                            <div class="form-group gap">
                                <label class="primary-label" for='{{ $idColorTextoTipoRecompensaInput }}'>Color de texto:</label>
                                <input type="color" class="colorPicker cursorPointer" id='{{ $idColorTextoTipoRecompensaInput }}' title="Seleccionar color"
                                    oninput="fillPreviewColorTextoSpan(this, '{{ $idPreviewColorSpan }}')" name="colorTexto_TipoRecompensa" value="#3206B0">
                            </div>
                            <div class="form-group colorFondoGap">
                                <label class="primary-label" for='{{ $idColorFondoTipoRecompensaInput }}'>Color de fondo:</label>
                                <input type="color" class="colorPicker cursorPointer" id='{{ $idColorFondoTipoRecompensaInput }}' title="Seleccionar color"
                                    oninput="fillPreviewColorFondoSpan(this, '{{ $idPreviewColorSpan }}')" name="colorFondo_TipoRecompensa" value="#DCD5F0">
                            </div>
                        </div>
                        
                        <div class="previewTipoRecompensaContainer">
                            <label class="primary-label noEditable">Previsualización:</label>
                            <span class="previewTipoRecompensa" id="{{ $idPreviewColorSpan }}" style="color:#3206B0; background-color: #DCD5F0;"></span> 
                        </div>
                    </div>

                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'></span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditarTipoRecompensa')">Cancelar</button>
                <button type="button" class="btn btn-primary update" 
                        onclick="guardarModalEditarTipoRecompensa('modalEditarTipoRecompensa', 'formEditarTipoRecompensa',
                                                    {{ json_encode($tiposRecompensasDB) }}, {{ json_encode($recompensasDB) }})">Actualizar</button>
            </div>
        </div>
    </div>
</div>


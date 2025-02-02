<div class="modal first"  id="modalEditarOficio">
    <div class="modal-dialog" id="modalEditarOficio-dialog">
        <div class="modal-content" id="modalEditarOficio-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar oficio</h5>
                <button class="close noUserSelect" onclick="closeModal('modalEditarOficio')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEditarOficio">
                <form id="formEditarOficio" action="{{ route('oficios.update') }}" method="POST">
                    @method('PUT')
                    @csrf
                    <!-- Variables globales -->
                    @php
                        $oficiosDB = $oficios;
                        $idSearchMessageError = 'searchEditOficioError';
                        $idCodigoOficioInput = 'codigoOficioInputEdit';
                        $idOptions = 'oficioEditOptions';
                        $idDescripcionOficioInputEdit = 'descripcionOficioInputEdit';
                        $someHiddenIdInputsArray = ['idNumberOficio'];
                        $otherInputsArray = [$idDescripcionOficioInputEdit];
                        $idGeneralMessageError = 'generalEditOficioError';
                        $searchDBField = 'idOficio';
                        $dbFieldsNameArray = ['descripcion_Oficio'];
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idOficio">
                   
                    <div class="form-group start paddingY" id="idH5EditOficioModalContainer">
                        <h5> *Solo puede editar la descripción de un oficio previamente creado.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="oficioEditSelect">Oficio:</label>
                        <div class="input-select" id="oficioEditSelect">
                            <input class="input-select-item" type="text" id='{{ $idCodigoOficioInput }}' maxlength="100" placeholder="Código - Nombre" autocomplete="off"
                                oninput="filterOptions('{{ $idCodigoOficioInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTimeIDInteger(this, '{{ $idOptions }}', '{{ $idSearchMessageError }}', 
                                        {{ json_encode($someHiddenIdInputsArray) }}, {{ json_encode($otherInputsArray) }}, 
                                        {{ json_encode($oficiosDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }})"

                                onclick="toggleOptions('{{ $idCodigoOficioInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @if (count($oficiosDB) > 0)
                                    @foreach ($oficiosDB as $oficio)
                                        @php
                                            $idNumberOficio = htmlspecialchars($oficio->idOficio, ENT_QUOTES, 'UTF-8');
                                            $codigoOficio = htmlspecialchars($oficio->codigoOficio, ENT_QUOTES, 'UTF-8');
                                            $nombreOficio = htmlspecialchars($oficio->nombre_Oficio, ENT_QUOTES, 'UTF-8');
                                            $descripcionOficio = htmlspecialchars($oficio->descripcion_Oficio, ENT_QUOTES, 'UTF-8');
                                            $value = $codigoOficio . " | " . $nombreOficio;
                                        @endphp
                                
                                        <li onclick="selectOptionEditOficio('{{ $value }}', '{{ $idNumberOficio }}', '{{ $descripcionOficio }}', 
                                                    '{{ $idCodigoOficioInput }}', '{{ $idOptions }}', {{ json_encode($someHiddenIdInputsArray) }})">
                                            {{ $value }}
                                        </li>   
                                    @endforeach
                                @else
                                    <li>
                                        No hay oficios registrados aún
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <span class="noInline-alert-message" id='{{ $idSearchMessageError }}'>No se encontró el oficio buscado</span>      
                    </div>
                   
                    <div class="form-group gap">
                        <label class="primary-label" for='{{ $idDescripcionOficioInputEdit }}'>Descripción:</label>
                        <textarea class="textarea normal" id='{{ $idDescripcionOficioInputEdit }}' name="descripcion_Oficio" 
                                  placeholder="Breve descripción"></textarea>
                    </div>
                    
                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'>  </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditarOficio')">Cancelar</button>
                <button type="button" class="btn btn-primary update" 
                        onclick="guardarModalEditarOficio('modalEditarOficio', 'formEditarOficio')">Actualizar</button>
            </div>
        </div>
    </div>
</div>


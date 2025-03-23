<div class="modal first"  id="modalEliminarOficio">
    <div class="modal-dialog" id="modalEliminarOficio-dialog">
        <div class="modal-content" id="modalEliminarOficio-content">
            <div class="modal-header">
                <h5 class="modal-title">Inhabilitar Oficio</h5>
                <button class="close noUserSelect" onclick="closeModal('modalEliminarOficio')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEliminarOficio">
                <form id="formEliminarOficio" action="{{ route('oficios.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <!-- Variables globales -->
                    @php
                        $oficiosDB = $oficios;
                        $idCodigoOficioInput = 'codigoOficioInputDelete';
                        $idOptions = 'oficioDeleteOptions';
                        $idMessageError = 'searchDeleteOficioError';
                        $idGeneralMessageError = 'generalDeleteOficioError';
                        $idDescripcionOficioInputDelete = 'descripcionOficioInputDelete';
                        $someHiddenIdInputsArray = ['idDeleteOficioInput'];
                        $otherInputsArray = [$idDescripcionOficioInputDelete];
                        $searchDBField = 'idOficio';
                        $dbFieldsNameArray = ['descripcion_Oficio'];
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idOficio">
                   
                    <div class="form-group start paddingY" id="idH5DeleteOficioModalContainer">
                        <h5>Seleccione el oficio que desee inhabilitar.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="OficioDeleteSelect">Oficio:</label>
                        <div class="input-select" id="OficioDeleteSelect">
                            <input class="input-select-item" type="text" id='{{ $idCodigoOficioInput }}' maxlength="100" placeholder="Código | Descripción" autocomplete="off"
                                oninput="filterOptions('{{ $idCodigoOficioInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTimeIDInteger(this, '{{ $idOptions }}', '{{ $idMessageError }}', 
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
                                
                                        <li onclick="selectOptionEliminarOficio('{{ $value }}', '{{ $idNumberOficio }}', '{{ $descripcionOficio }}', 
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
                        <span class="noInline-alert-message" id='{{ $idMessageError }}'>No se encontró la Oficio buscada</span>      
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" for='{{ $idDescripcionOficioInputDelete }}'>Descripción:</label>
                        <textarea class="textarea normal" id='{{ $idDescripcionOficioInputDelete }}' placeholder="Breve descripción" disabled></textarea>
                    </div>
                
                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'> </span>      
                    </div>    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEliminarOficio')">Cancelar</button>
                <button type="button" class="btn btn-primary delete" 
                        onclick="guardarModalEliminarOficio('modalEliminarOficio', 'formEliminarOficio')">Inhabilitar</button>
            </div>
        </div>
    </div>
</div>


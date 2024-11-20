<div class="modal first"  id="modalRestaurarOficio">
    <div class="modal-dialog" id="modalRestaurarOficio-dialog">
        <div class="modal-content" id="modalRestaurarOficio-content">
            <div class="modal-header">
                <h5 class="modal-title">Restaurar Oficio</h5>
                <button class="close" onclick="closeModal('modalRestaurarOficio')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyRestaurarOficio">
                <form id="formRestaurarOficio" action="{{ route('oficios.restore') }}" method="POST">
                    @csrf
                    <!-- Variables globales -->
                    @php
                        $oficiosEliminadosDB = $oficiosEliminados;
                        $codigoInputOficioRestaurar = 'codigoOficioInputRestaurar';
                        $idOptions = 'oficioRestaurarOptions';
                        $idMessageError = 'searchRestaurarOficioError';
                        $idGeneralMessageError = 'generalRestaurarOficioError';
                        $idDescripcionOficioInputRestaurar = 'descripcionOficioInputRestaurar';
                        $someHiddencodigoInputOficioRestaurarsArray = ['idNumberOficioInputRestaurar'];
                        $otherInputsArray = [$idDescripcionOficioInputRestaurar];
                        $searchDBField = 'idOficio';
                        $dbFieldsNameArray = ['nombre_Oficio', 'descripcion_Oficio'];
                    @endphp
                    <input type="hidden" id='{{ $someHiddencodigoInputOficioRestaurarsArray[0] }}' maxlength="9" name="idOficio">
                   
                    <div class="form-group start paddingY" id="idH5RestaurarOficioModalContainer">
                        <h5>*Solo puede restaurar oficios eliminados.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="OficioRestaurarSelect">Oficio:</label>
                        <div class="input-select" id="OficioRestaurarSelect">
                            <input class="input-select-item" type="text" id='{{ $codigoInputOficioRestaurar }}' maxlength="100" placeholder="Código | Nombre" autocomplete="off"
                                oninput="filterOptions('{{ $codigoInputOficioRestaurar }}', '{{ $idOptions }}'),
                                        validateValueOnRealTimeIDInteger(this, '{{ $idOptions }}', '{{ $idMessageError }}', 
                                        {{ json_encode($someHiddencodigoInputOficioRestaurarsArray) }}, {{ json_encode($otherInputsArray) }}, 
                                        {{ json_encode($oficiosEliminadosDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }})"

                                onclick="toggleOptions('{{ $codigoInputOficioRestaurar }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @foreach ($oficiosEliminadosDB as $oficio)
                                    @php
                                        $idNumberOficio = htmlspecialchars($oficio->idOficio, ENT_QUOTES, 'UTF-8');
                                        $codigoOficio = htmlspecialchars($oficio->codigoOficio, ENT_QUOTES, 'UTF-8');
                                        $nombreOficio = htmlspecialchars($oficio->nombre_Oficio, ENT_QUOTES, 'UTF-8');
                                        $descripcionOficio = htmlspecialchars($oficio->descripcion_Oficio, ENT_QUOTES, 'UTF-8');
                                        $value = $codigoOficio . " | " . $nombreOficio;
                                    @endphp
                            
                                    <li onclick="selectOptionRestaurarOficio('{{ $value }}', '{{ $idNumberOficio }}', '{{ $descripcionOficio }}', 
                                                '{{ $codigoInputOficioRestaurar }}', '{{ $idOptions }}', {{ json_encode($someHiddencodigoInputOficioRestaurarsArray) }})">
                                        {{ $value }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <span class="noInline-alert-message" id='{{ $idMessageError }}'>No se encontró el oficio buscado</span>      
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" for='{{ $idDescripcionOficioInputRestaurar }}'>Descripción:</label>
                        <textarea class="textarea normal" id='{{ $idDescripcionOficioInputRestaurar }}' placeholder="Breve descripción" disabled></textarea>
                    </div>
                
                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'>  </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRestaurarOficio')">Cancelar</button>
                <button type="button" class="btn btn-primary recover" 
                        onclick="guardarModalRestaurarOficio('modalRestaurarOficio', 'formRestaurarOficio')">Restaurar</button>
            </div>
        </div>
    </div>
</div>


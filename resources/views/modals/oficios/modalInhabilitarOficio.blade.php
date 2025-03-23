<div class="modal first" id="modalInhabilitarOficio">
    <div class="modal-dialog" id="modalInhabilitarOficio-dialog">
        <div class="modal-content" id="modalInhabilitarOficio-content">
            <div class="modal-header">
                <h5 class="modal-title">Inhabilitar Oficio</h5>
                <button class="close noUserSelect" onclick="closeModal('modalInhabilitarOficio')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyInhabilitarOficio">
                <form id="formInhabilitarOficio" action="{{ route('oficios.disable') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <!-- Variables globales -->
                    @php
                        $oficiosDB = $oficios;
                        $idCodigoOficioInput = 'codigoOficioInputDisable';
                        $idOptions = 'oficioDisableOptions';
                        $idMessageError = 'searchDisableOficioError';
                        $idGeneralMessageError = 'generalDisableOficioError';
                        $idDescripcionOficioInputDisable = 'descripcionOficioInputDisable';
                        $someHiddenIdInputsArray = ['idDisableOficioInput'];
                        $otherInputsArray = [$idDescripcionOficioInputDisable];
                        $searchDBField = 'idOficio';
                        $dbFieldsNameArray = ['descripcion_Oficio'];
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idOficio">

                    <div class="form-group start paddingY" id="idH5DisableOficioModalContainer">
                        <h5>Seleccione el oficio que desee inhabilitar.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="oficioDisableSelect">Oficio:</label>
                        <div class="input-select" id="oficioDisableSelect">
                            <input class="input-select-item" type="text" id='{{ $idCodigoOficioInput }}'
                                maxlength="100" placeholder="Código | Descripción" autocomplete="off"
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
                                            $codigoOficio = htmlspecialchars(
                                                $oficio->codigoOficio,
                                                ENT_QUOTES,
                                                'UTF-8',
                                            );
                                            $nombreOficio = htmlspecialchars(
                                                $oficio->nombre_Oficio,
                                                ENT_QUOTES,
                                                'UTF-8',
                                            );
                                            $descripcionOficio = htmlspecialchars(
                                                $oficio->descripcion_Oficio,
                                                ENT_QUOTES,
                                                'UTF-8',
                                            );
                                            $value = $codigoOficio . ' | ' . $nombreOficio;
                                        @endphp

                                        <li
                                            onclick="selectOptionInhabilitarOficio('{{ $value }}', '{{ $idNumberOficio }}', '{{ $descripcionOficio }}', 
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
                        <span class="noInline-alert-message" id='{{ $idMessageError }}'>No se encontró el oficio
                            buscado</span>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable"
                            for='{{ $idDescripcionOficioInputDisable }}'>Descripción:</label>
                        <textarea class="textarea normal" id='{{ $idDescripcionOficioInputDisable }}' placeholder="Breve descripción" disabled></textarea>
                    </div>

                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $idGeneralMessageError }}'> </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="modalFooterInhabilitarOficio">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('modalInhabilitarOficio')">Cancelar</button>
                <button type="button" class="btn btn-primary disable"
                    onclick="guardarModalInhabilitarOficio('modalInhabilitarOficio', 'formInhabilitarOficio')">Inhabilitar</button>
            </div>
        </div>
    </div>
</div>

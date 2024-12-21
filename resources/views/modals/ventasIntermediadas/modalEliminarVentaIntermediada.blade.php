<div class="modal first" id="modalEliminarVentaIntermediada">
    <div class="modal-dialog" id="modalEliminarVentaIntermediada-dialog">
        <div class="modal-content" id="modalEliminarVentaIntermediada-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Venta Intermediada</h5>
                <button class="close noUserSelect" onclick="closeModal('modalEliminarVentaIntermediada')">&times;</button>
            </div>
            <div class="modal-body" id="modalEliminarVentaIntermediada-body">
                <!-- Formulario para agregar nueva venta -->
                <form id="formEliminarVenta" action="{{ route('ventasIntermediadas.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    @php
                        $idInput = 'ventaTecnicoInputDelete';
                        $idOptions = 'ventaOptions';
                        $idMessageError = 'eliminarVentaMessageError';
                        $someHiddenIdInputsArray = ['idVentaIntermediada'];
                        $fechaEmisionInput = 'fechaEmisionVentaIntermediadaInputDelete';    
                        $horaEmisionInput = 'horaEmisionVentaIntermediadaInputDelete';
                        $montoTotalInput = 'montoTotalInputDelete';
                        $puntosGeneradosInput = 'puntosGeneradosInputDelete';
                        $tipoCodigoClienteInput = 'tipoCodigoClienteInputDelete';
                        $codigoClienteInput = 'codigoClienteInputDelete';
                        $nombreClienteInput = 'nombreClienteInputDelete';
                        $ventasDB = $ventas;
                        $otherInputsArray = [$fechaEmisionInput , $horaEmisionInput, $montoTotalInput, $puntosGeneradosInput, $tipoCodigoClienteInput,
                                            $codigoClienteInput, $nombreClienteInput];
                        $dbFieldsNameArray = ['fechaVenta', 'horaVenta', 'montoTotal_VentaIntermediada',
                                            'puntosGanados_VentaIntermediada', 'tipoCodigoCliente_VentaIntermediada', 'codigoCliente_VentaIntermediada',
                                            'nombreCliente_VentaIntermediada'];
                        $searchDBField = 'idVentaIntermediada';
                    @endphp

                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' name="idVentaIntermediada" readonly>

                    <div class="form-group start paddingY" id="idH5DeleteVentaModalContainer">
                        <h5>Seleccione la venta que desee eliminar.</h5>
                    </div>

                    <div class="form-group">
                        <label class="primary-label" id="idLabelTecnico">Venta</label>
                    </div>
                    <div class="form-group start">
                        <div class="input-select" id="tecnicoSelect">
                            <div class="tooltip-container">
                                <span class="tooltip red" id="idTecnicoTooltip">Este es el mensaje del tooltip</span>
                            </div>
                            <input class="input-select-item" type="text" id='{{ $idInput }}' maxlength="50" placeholder="Número de venta | Técnico"
                                oninput="filterOptions('{{ $idInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTime(this, '{{ $idOptions }}', '{{ $idMessageError }}', 
                                        {{ json_encode($someHiddenIdInputsArray) }}, {{ json_encode($otherInputsArray) }}, 
                                        {{ json_encode($ventasDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }})"
                                onclick="toggleOptions('{{ $idInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @foreach ($ventasDB as $venta)
                                    @php
                                        $fechaEmision = htmlspecialchars($venta->fechaVenta, ENT_QUOTES, 'UTF-8');
                                        $horaEmision = htmlspecialchars($venta->horaVenta, ENT_QUOTES, 'UTF-8');
                                        $montoTotal = htmlspecialchars($venta->montoTotal_VentaIntermediada, ENT_QUOTES, 'UTF-8');
                                        $puntosGenerados = htmlspecialchars($venta->puntosGanados_VentaIntermediada, ENT_QUOTES, 'UTF-8');
                                        $tipoCodigoCliente = htmlspecialchars($venta->tipoCodigoCliente_VentaIntermediada, ENT_QUOTES, 'UTF-8');
                                        $codigoCliente = htmlspecialchars($venta->codigoCliente_VentaIntermediada, ENT_QUOTES, 'UTF-8');
                                        $nombreCliente = htmlspecialchars($venta->nombreCliente_VentaIntermediada, ENT_QUOTES, 'UTF-8');
                                        $value = $venta->idVentaIntermediada . " | " . $venta->idTecnico . "-" . $venta->nombreTecnico;
                                    @endphp
                                    <li onclick="selectOptionEliminarVenta('{{ $value }}', '{{ $idInput }}', '{{ $idOptions }}', '{{ $fechaEmision }}',
                                                '{{ $horaEmision }}', '{{ $montoTotal }}', '{{ $puntosGenerados }}', '{{ $tipoCodigoCliente }}', '{{ $codigoCliente }}',
                                                '{{ $nombreCliente }}')">
                                        {{ $value }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <span class="inline-alert-message" id='{{ $idMessageError }}'> No se encontró la venta buscada </span>      
                    </div>

                    <div class="form-group gap">
                        <div class = "group-items dateTime">
                            <label class="secondary-label centered"> Fecha y hora de emisión </label>
                            <div class="dateTimeContainer">
                                <input class="input-item date" id='{{ $fechaEmisionInput }}' type="text" oninput="validateManualDateInput(this), updateDateInput(this)" maxlength="10"
                                    placeholder="aaaa-mm-dd" disabled>
                                <input class="input-item time" id='{{ $horaEmisionInput }}' type="text" oninput="validateManualTimeInput(this), updateTimeInput(this)"  maxlength="8"
                                    placeholder="hh:mm:ss" disabled>
                            </div>
                        </div>
                        <div class="group-items">
                            <label class="secondary-label centered">Monto total</label>
                            <input class="input-item" id='{{ $montoTotalInput }}' type="text" oninput="validateRealTimeInputLength(this, 8), validatePositiveFloat(this)" 
                                   placeholder="25.50" disabled>
                        </div>
                        <div class="group-items">
                            <label class="important-label"> Puntos generados </label>
                            <input class="input-item centered readonly" id='{{ $puntosGeneradosInput }}' placeholder="26" disabled>
                        </div>
                    </div>
                    
                    <div class="form-group start marginTop">
                        <label class="primary-label noEditable" id="labelClienteAgregarVenta"> Cliente </label>
                    </div>

                    <div class="form-group gap">
                        <div class = "group-items">
                            <label class="secondary-label"> Tipo de Documento </label>
                            <x-onlySelect-input 
                                :idSelect="'tipoDocumentoSelect'"
                                :inputClassName="'onlySelectInput noHandCursor'"
                                :idInput="$tipoCodigoClienteInput"
                                :idOptions="'tipoDocumentoOptions'"
                                :placeholder="'DNI/RUC'"
                                :options="['DNI', 'RUC']"
                                :spanClassName="'noUserSelect noHandCursor'"
                                :disabled="true"
                                :focusBorder="'noFocusBorder'"
                            />
                        </div>
                        <div class = "group-items">
                            <label class="secondary-label"> Número de documento </label>
                            <input class="input-item" id='{{ $codigoClienteInput }}' maxlength="11" oninput="updateDNIRUCMaxLength(this), validateNumberRealTime(this)"
                                    placeholder="12345678" disabled>
                        </div>
                        <div class = "group-items">
                            <label class="secondary-label"> Nombre </label>
                            <input class="input-item" id='{{ $nombreClienteInput }}' oninput="validateRealTimeInputLength(this, 60)"
                                    placeholder="AP. PATERNO AP. MATERNO, NOMBRE" disabled>
                        </div>
                    </div>  

                    
                </form>
            </div>

            <div class="form-group start">
                <span class="inline-alert-message" id="eliminarVentaMultiMessageError"> multiMessageError2 </span> 
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEliminarVentaIntermediada')">Cancelar</button>
                <button type="button" class="btn btn-primary delete"
                        onclick="guardarModalEliminarVentaIntermediada('modalEliminarVentaIntermediada', 'formEliminarVenta')">Eliminar</button>
            </div>
        </div>
    </div>
</div>

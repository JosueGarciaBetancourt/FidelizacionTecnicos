<div class="modal first" id="modalEliminarVentaIntermediada">
    <div class="modal-dialog" id="modalEliminarVentaIntermediada-dialog">
        <div class="modal-content" id="modalEliminarVentaIntermediada-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Venta Intermediada</h5>
                <button class="close noUserSelect" onclick="closeModal('modalEliminarVentaIntermediada')">&times;</button>
            </div>
            <div class="modal-body" id="modalEliminarVentaIntermediada-body">
                <!-- Formulario para agregar nueva venta -->
                <form id="formAgregarVenta" action="{{ route('ventasIntermediadas.store') }}" method="POST">
                    @csrf
                    <!-- Variables globales -->
                    @php
                        $idInput = 'ventaInput';
                        $idOptions = 'ventaOptions';
                        $idMessageError = 'eliminarVentaMessageError';
                        $someHiddenIdInputsArray = ['idVentaIntermediada'];
                        $ventasDB = $ventas;
                    @endphp
                    <!-- Campos ocultos para el formulario -->
                    <input type="text" id='{{ $someHiddenIdInputsArray[0] }}' disabled>

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
                                        {{ json_encode($someHiddenIdInputsArray) }})" 
                                onclick="toggleOptions('{{ $idInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @foreach ($ventas as $venta)
                                    @php
                                        $value = $venta->idVentaIntermediada . " | " . $venta->idTecnico . "-" . $venta->nombreTecnico;
                                    @endphp
                                    <li onclick="selectOptionEliminarVenta('{{ $value }}', '{{ $idInput }}', '{{ $idOptions }}')">
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
                                <div class="tooltip-container">
                                    <span class="tooltip" id="idFechaEmisionTooltip">Este es el mensaje del tooltip</span>
                                </div>
                                <input class="input-item date" id="fechaEmisionVentaIntermediadaInput" type="text"
                                        oninput="validateManualDateInput(this), updateDateInput(this)" maxlength="10"
                                        placeholder="aaaa-mm-dd" disabled>

                                <div class="tooltip-container">
                                    <span class="tooltip" id="idHoraEmisionTooltip">Este es el mensaje del tooltip</span>
                                </div>
                                <input class="input-item time" id="horaEmisionVentaIntermediadaInput" type="text"
                                        oninput="validateManualTimeInput(this), updateTimeInput(this)"  maxlength="8"
                                        placeholder="hh:mm:ss" disabled>
                                <input type="hidden" id="fechaHoraEmisionVentaIntermediadaInput" name="fechaHoraEmision_VentaIntermediada">
                            </div>
                        </div>
                        <div class="group-items">
                            <label class="secondary-label centered">Monto total</label>
                            <input class="input-item" id="montoTotalInput" name="montoTotal_VentaIntermediada" type="text" 
                                   oninput="validateRealTimeInputLength(this, 8), validatePositiveFloat(this)" 
                                   placeholder="25.50" disabled>
                        </div>
                        <div class="group-items">
                            <label class="important-label"> Puntos generados </label>
                            <input class="input-item centered readonly" id="puntosGanadosInput" name="puntosGanados_VentaIntermediada"  
                                placeholder="26" disabled>
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
                                :idInput="'tipoCodigoClienteInput'"
                                :idOptions="'tipoDocumentoOptions'"
                                :placeholder="'DNI/RUC'"
                                :name="'tipoCodigoCliente_VentaIntermediada'"
                                :options="['DNI', 'RUC']"
                                :spanClassName="'noUserSelect noHandCursor'"
                                :disabled="true"
                                :focusBorder="'noFocusBorder'"
                            />
                        </div>
                        <div class = "group-items">
                            <label class="secondary-label"> Número de documento </label>
                            <div class="tooltip-container">
                                <span class="tooltip red" id="idCodigoClienteTooltip">Este es el mensaje del tooltip</span>
                            </div>
                            <input class="input-item" id="idClienteInput" name="codigoCliente_VentaIntermediada" maxlength="11"
                                   oninput="updateDNIRUCMaxLength(this), validateNumberRealTime(this)" placeholder="12345678" disabled>
                        </div>
                        <div class = "group-items">
                            <label class="secondary-label"> Nombre </label>
                            <input class="input-item" id="nombreClienteInput" name="nombreCliente_VentaIntermediada"
                                   oninput="validateRealTimeInputLength(this, 60)" placeholder="AP. PATERNO AP. MATERNO, NOMBRE" disabled>
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
                        onclick="guardarmodalEliminarVentaIntermediada('modalEliminarVentaIntermediada', 'formAgregarVenta', {{ json_encode($ventasDB) }})">Eliminar</button>
            </div>
        </div>
    </div>
</div>

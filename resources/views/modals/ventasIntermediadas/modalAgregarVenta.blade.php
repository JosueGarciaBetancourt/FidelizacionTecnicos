<div class="modal first" id="modalAgregarVenta">
    <div class="modal-dialog" id="modalAgregarVenta-dialog">
        <div class="modal-content" id="modalAgregarVenta-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Venta Intermediada</h5>
                <button class="close" onclick="closeModal('modalAgregarVenta')">&times;</button>
            </div>
            <div class="modal-body" id="modalAgregarVenta-body">
                <!-- Formulario para agregar nueva venta -->
                <form id="formAgregarVenta" action="{{ route('ventasIntermediadas.store') }}" method="POST">
                    @csrf
                    <!-- Variables globales -->
                    @php
                        $idInput = 'tecnicoInput';
                        $idOptions = 'tecnicoOptions';
                        $idMessageError = 'nuevaVentaMessageError';
                        $someHiddenIdInputsArray = ['idTecnicoInput', 'nombreTecnicoInput'];
                        $ventasDB = $ventas;
                    @endphp
                    <!-- Campos ocultos para el formulario -->
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' name="idTecnico">
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[1] }}' name="nombreTecnico">
                    <div class="form-group marginTop">
                        <label class="primary-label" id="idLabelTecnico">
                            Técnico 
                            <a onclick="openModal('modalAgregarNuevoTecnico', 'ventasIntermediadas.create')">[+ Nuevo]</a>
                        </label>
                    </div>
                    <div class="form-group start">
                        <div class="input-select" id="tecnicoSelect">
                            <div class="tooltip-container">
                                <span class="tooltip red" id="idTecnicoTooltip">Este es el mensaje del tooltip</span>
                            </div>
                            <input class="input-select-item" type="text" id='{{ $idInput }}' maxlength="50" placeholder="DNI - Nombre"
                                oninput="filterOptions('{{ $idInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTime(this, '{{ $idOptions }}', '{{ $idMessageError }}',
                                        {{ json_encode($someHiddenIdInputsArray) }})" 
                                onclick="toggleOptions('{{ $idInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @foreach ($tecnicos as $tecnico)
                                    @php
                                        $value = $tecnico->idTecnico . " - " . $tecnico->nombreTecnico;
                                    @endphp
                                    <li onclick="selectOptionAgregarVenta('{{ $value }}', '{{ $idInput }}', '{{ $idOptions }}')">
                                        {{ $value }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <span class="inline-alert-message" id='{{ $idMessageError }}'> No se encontró el técnico buscado </span>      
                    </div>
                    
                    <div class="form-group start marginTop">
                        <label class="primary-label" id="labelClienteAgregarVenta"> Cliente </label>
                    </div>

                    <div class="form-group gap">
                        <div class = "group-items">
                            <label class="secondary-label"> Tipo de Documento </label>
                            <x-onlySelect-input 
                                :idSelect="'tipoDocumentoSelect'"
                                :inputClassName="'onlySelectInput'"
                                :idInput="'tipoCodigoClienteInput'"
                                :idOptions="'tipoDocumentoOptions'"
                                :placeholder="'DNI/RUC'"
                                :name="'tipoCodigoCliente_VentaIntermediada'"
                                :options="['DNI', 'RUC']"
                            />
                        </div>
                        <div class = "group-items">
                            <label class="secondary-label"> Número de documento </label>
                            <div class="tooltip-container">
                                <span class="tooltip red" id="idCodigoClienteTooltip">Este es el mensaje del tooltip</span>
                            </div>
                            <input class="input-item" id="idClienteInput" name="codigoCliente_VentaIntermediada" maxlength="11"
                                   oninput="updateDNIRUCMaxLength(this), validateNumberRealTime(this)" placeholder="12345678">
                        </div>
                        <div class = "group-items">
                            <label class="secondary-label"> Nombre </label>
                            <input class="input-item" id="nombreClienteInput" name="nombreCliente_VentaIntermediada"
                                   oninput="validateRealTimeInputLength(this, 60)" placeholder="AP. PATERNO AP. MATERNO, NOMBRE">
                        </div>
                    </div>  

                    <div class="form-group start marginTop">
                        <label class="primary-label" id="labelVentaAgregarVenta"> Venta </label>
                    </div>

                    <div class="form-group gap">
                        <div class = "group-items">
                            <label class="secondary-label"> Número de comprobante </label>
                            <div class="tooltip-container">
                                <span class="tooltip" id="idNumComprobanteTooltip">Este es el mensaje del tooltip</span>
                            </div>
                            <input class="input-item" id="idVentaIntermediadaInput" name="idVentaIntermediada"
                                   oninput="validateNumComprobanteInput(this)" maxlength="13" placeholder="B001-72">
                        </div>
                        <div class = "group-items dateTime">
                            <label class="secondary-label centered"> Fecha y hora de emisión </label>
                            <div class="dateTimeContainer">
                                <div class="tooltip-container">
                                    <span class="tooltip" id="idFechaEmisionTooltip">Este es el mensaje del tooltip</span>
                                </div>
                                <input class="input-item date" id="fechaEmisionVentaIntermediadaInput" type="text"
                                        oninput="validateManualDateInput(this), updateDateInput(this)" maxlength="10"
                                        placeholder="aaaa-mm-dd">

                                <div class="tooltip-container">
                                    <span class="tooltip" id="idHoraEmisionTooltip">Este es el mensaje del tooltip</span>
                                </div>
                                <input class="input-item time" id="horaEmisionVentaIntermediadaInput" type="text"
                                        oninput="validateManualTimeInput(this), updateTimeInput(this)"  maxlength="8"
                                        placeholder="hh:mm:ss">
                                <input type="hidden" id="fechaHoraEmisionVentaIntermediadaInput" name="fechaHoraEmision_VentaIntermediada">
                            </div>
                        </div>
                        <div class="group-items">
                            <label class="secondary-label centered">Monto total</label>
                            <input class="input-item" id="montoTotalInput" name="montoTotal_VentaIntermediada" type="text" 
                                   oninput="validateRealTimeInputLength(this, 8), validatePositiveFloat(this)" 
                                   placeholder="25.50">
                        </div>
                    </div>
                    
                    <div class="form-group start marginTop">
                        <label class="important-label"> Puntos generados </label>
                    </div>
                    
                    <div class="form-group start">
                        <input class="input-item readonly" id="puntosGanadosInput" name="puntosGanados_VentaIntermediada"  placeholder="26" readonly>
                    </div>
                </form>
                <!-- Seleccionar archivos -->
                <div class="select-files-div" id="idSelectFilesContainer">
                    <div class="fileArea" id="fileArea" class="select-files-div" ondragover="allowDrop(event)"
                         ondragleave="removeDrop(event)" ondrop="handleDrop(event)">
                         <div class="fileArea_text">
                            <input type="file" id="fileInput" class="file-input" accept=".xml">
                            <button type="button" class="btnSelectFile" onclick="handleFileSelect()">Seleccionar archivo .xml</button>
                            <span>o arrastra y suelta aquí</span>
                         </div>
                    </div>
                </div>
            </div>

            <div class="form-group start">
                <span class="inline-alert-message" id="multiMessageError2"> multiMessageError2 </span> 
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalAgregarVenta')">Cancelar</button>
                <button type="button" class="btn btn-primary"
                        onclick="guardarModalAgregarVenta('modalAgregarVenta', 'formAgregarVenta', {{ json_encode($ventasDB) }})">Guardar</button>
            </div>
        </div>
    </div>
</div>

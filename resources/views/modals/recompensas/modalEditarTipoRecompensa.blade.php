<div class="modal first"  id="modalEditarTipoRecompensa">
    <div class="modal-dialog" id="modalEditarTipoRecompensa-dialog">
        <div class="modal-content" id="modalEditarTipoRecompensa-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar tipo de recompensa</h5>
                <button class="close" onclick="closeModal('modalEditarTipoRecompensa')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEditarRecompensa">
                <form id="formEditarRecompensa" action="{{ route('recompensas.update') }}" method="POST">
                    @method('PUT')
                    @csrf
                    <!-- Variables globales -->
                    @php
                        $recompensasDB = $recompensasWithoutFirst;
                        $dbFieldsNameArray = ['tipoRecompensa', 'descripcionRecompensa', 'costoPuntos_Recompensa', 'stock_Recompensa'];
                        $idInput = 'recompensaEditInput';
                        $idOptions = 'recompensaEditOptions';
                        $idMessageError = 'searchEditRecompensaError';
                        $someHiddenIdInputsArray = ['idEditRecompensaInput'];
                        $idCostoPuntosInput = 'costoPuntosInputEdit'; //El valor se debe modificar también en modalEditarTipoRecompensa.js
                        $idStockInput = 'stockRecompensaInputEdit'; 
                        $idTipoRecompensaInputEdit = 'tipoRecompensaInputEdit';
                        $idDescripcionRecompensaInputEdit = 'descripcionRecompensaInputEdit';
                        $otherInputsArray = [ $idTipoRecompensaInputEdit , 'descripcionRecompensaInputEdit', $idCostoPuntosInput, $idStockInput];
                        $searchDBField = 'idRecompensa';
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idRecompensa">
                   
                    <div class="form-group start paddingY" id="idH5EditRecompensaModalContainer">
                        <h5> *Solo puede editar el costo unitario de una recompensa previamente creada.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="recompensaEditSelect">Recompensa:</label>
                        <div class="input-select" id="recompensaEditSelect">
                            <input class="input-select-item" type="text" id='{{ $idInput }}' maxlength="100" placeholder="Código | Descripción"
                                oninput="filterOptions('{{ $idInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTime(this, '{{ $idOptions }}', '{{ $idMessageError }}', 
                                        {{ json_encode($someHiddenIdInputsArray) }}, {{ json_encode($otherInputsArray) }}, 
                                        {{ json_encode($recompensasDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }})"

                                onclick="toggleOptions('{{ $idInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @foreach ($recompensasWithoutFirst as $recompensa)
                                    @php
                                        $idRecompensa = htmlspecialchars($recompensa->idRecompensa, ENT_QUOTES, 'UTF-8');
                                        $descripcionRecompensa = htmlspecialchars($recompensa->descripcionRecompensa, ENT_QUOTES, 'UTF-8');
                                        $costoPuntos = htmlspecialchars($recompensa->costoPuntos_Recompensa, ENT_QUOTES, 'UTF-8');
                                        $stockRecompensa = htmlspecialchars($recompensa->stock_Recompensa, ENT_QUOTES, 'UTF-8');
                                        $tipoRecompensa = htmlspecialchars($recompensa->tipoRecompensa, ENT_QUOTES, 'UTF-8');
                                        $value = $idRecompensa . " | " . $descripcionRecompensa;
                                    @endphp
                            
                                   <li onclick="selectOptionEditarRecompensa('{{ $value }}', '{{ $idRecompensa }}', '{{ $descripcionRecompensa }}', '{{ $costoPuntos }}',
                                                '{{ $stockRecompensa }}', '{{ $tipoRecompensa }}', '{{ $idInput }}', '{{ $idOptions }}', {{ json_encode($someHiddenIdInputsArray) }})">
                                        {{ $value }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <span class="noInline-alert-message" id='{{ $idMessageError }}'>No se encontró la recompensa buscada</span>      
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="tipoRecompensaLabelEdit" for="tipoRecompensaInput">Tipo:</label>
                        <x-onlySelect-input 
                            :idInput="$idTipoRecompensaInputEdit"
                            :inputClassName="'onlySelectInput long noHandCursor'"
                            :placeholder="'Tipo de recompensa'"
                            :name="'tipoRecompensa'"
                            :options="['Accesorio', 'EPP', 'Herramienta']"
                            :disabled="true"
                            :spanClassName="'noHandCursor'"
                            :focusBorder="'noFocusBorder'"
                        />
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" for="idRecompensaDescripcion">Descripción:</label>
                        <textarea class="textarea normal" id="descripcionRecompensaInputEdit" name="descripcionRecompensa" 
                                  placeholder="Breve descripción" disabled></textarea>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label" for='{{ $idCostoPuntosInput }}'>Costo unitario (máx. 60000 puntos):</label>
                        <input class="input-item" id='{{ $idCostoPuntosInput }}' maxlength="5"
                                oninput="validateNumberWithMaxLimitRealTime(this, 60000)" placeholder="60000" name="costoPuntos_Recompensa" >
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for='{{ $idStockInput }}'>Stock (máx. 1000 unidades):</label>
                        <input class="input-item" id='{{ $idStockInput }}' maxlength="4"
                                oninput="validateNumberWithMaxLimitRealTime(this, 1000)" placeholder="1000" name="stock_Recompensa" >
                    </div>
                    
                    <div class="form-group start">
                        <span class="noInline-alert-message" id="editarRecompensaMessageError">  </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditarTipoRecompensa')">Cancelar</button>
                <button type="button" class="btn btn-primary update" 
                        onclick="guardarmodalEditarTipoRecompensa('modalEditarTipoRecompensa', 'formEditarRecompensa')">Actualizar</button>
            </div>
        </div>
    </div>
</div>

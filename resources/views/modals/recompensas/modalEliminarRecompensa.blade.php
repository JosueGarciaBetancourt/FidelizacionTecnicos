<div class="modal first" id="modalEliminarRecompensa">
    <div class="modal-dialog" id="modalEliminarRecompensa-dialog">
        <div class="modal-content" id="modalEliminarRecompensa-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar recompensa</h5>
                <button class="close noUserSelect" onclick="closeModal('modalEliminarRecompensa')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEliminarRecompensa">
                <form id="formEliminarRecompensa" action="{{ route('recompensas.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <!-- Variables globales -->
                    @php
                        $recompensasDB = $recompensas; // Solo recompensas activas
                        $dbFieldsNameArray = ['nombre_TipoRecompensa', 'descripcionRecompensa', 'costoPuntos_Recompensa', 'stock_Recompensa'];
                        $idInput = 'recompensaInputDelete';
                        $idOptions = 'recompensaDeleteOptions';
                        $idMessageError = 'searchDeleteRecompensaError';
                        $someHiddenIdInputsArray = ['idDeleteRecompensaInput'];
                        $idCostoPuntosInput = 'costoPuntosInputDelete'; // El valor se debe modificar también en modalEliminarRecompensa.js
                        $idStockRecompensaDelete = 'stockRecompensaInputDelete'; 
                        $idTipoRecompensaInputDelete = 'tipoRecompensaInputDelete';
                        $idDescripcionRecompensaInputDelete = 'descripcionRecompensaInputDelete';
                        $otherInputsArray = [$idTipoRecompensaInputDelete, $idDescripcionRecompensaInputDelete, $idCostoPuntosInput, $idStockRecompensaDelete];
                        $searchDBField = 'idRecompensa';
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="13" name="idRecompensa">
                   
                    <div class="form-group start paddingY" id="idH5DeleteRecompensaModalContainer">
                        <h5>Seleccione la recompensa que desee inhabilitar.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="recompensaDeleteSelect">Recompensa:</label>
                        <div class="input-select" id="recompensaDeleteSelect">
                            <input class="input-select-item" type="text" id='{{ $idInput }}' maxlength="100" placeholder="Código | Descripción" autocomplete="off"
                                oninput="filterOptions('{{ $idInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTime(this, '{{ $idOptions }}', '{{ $idMessageError }}', 
                                        {{ json_encode($someHiddenIdInputsArray) }}, {{ json_encode($otherInputsArray) }}, 
                                        {{ json_encode($recompensasDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }})"
                                onclick="toggleOptions('{{ $idInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @if (count($recompensasDB) > 0)
                                    @foreach ($recompensasDB as $recompensa)
                                        @php
                                            $idRecompensa = htmlspecialchars($recompensa->idRecompensa, ENT_QUOTES, 'UTF-8');
                                            $descripcionRecompensa = htmlspecialchars($recompensa->descripcionRecompensa, ENT_QUOTES, 'UTF-8');
                                            $costoPuntos = htmlspecialchars($recompensa->costoPuntos_Recompensa, ENT_QUOTES, 'UTF-8');
                                            $stockRecompensa = htmlspecialchars($recompensa->stock_Recompensa, ENT_QUOTES, 'UTF-8');
                                            $tipoRecompensa = htmlspecialchars($recompensa->nombre_TipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $value = $idRecompensa . " | " . $descripcionRecompensa;
                                        @endphp
                                
                                    <li onclick="selectOptionEliminarRecompensa('{{ $value }}', '{{ $idRecompensa }}', '{{ $descripcionRecompensa }}', 
                                            '{{ $costoPuntos }}', '{{ $stockRecompensa }}', '{{ $tipoRecompensa }}', '{{ $idInput }}', '{{ $idOptions }}',
                                            {{ json_encode($someHiddenIdInputsArray) }})">
                                            {{ $value }}
                                        </li>
                                    @endforeach
                                @else
                                    <li>
                                        No hay recompensas registradas aún
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <span class="noInline-alert-message" id='{{ $idMessageError }}'>No se encontró la recompensa buscada</span>      
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="tipoRecompensaLabelDelete" for="tipoRecompensaInput">Tipo:</label>
                        <x-onlySelect-input 
                            :idInput="$idTipoRecompensaInputDelete"
                            :inputClassName="'onlySelectInput long noHandCursor'"
                            :placeholder="'Tipo de recompensa'"
                            :name="'tipoRecompensa'"
                            :options="['Accesorio', 'EPP', 'Herramienta']"
                            :disabled="true"
                            :spanClassName="'noUserSelect noHandCursor'"
                            :focusBorder="'noFocusBorder'"
                        />
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" for="idRecompensaDescripcion">Descripción:</label>
                        <textarea class="textarea normal" id="descripcionRecompensaInputDelete" placeholder="Breve descripción" disabled></textarea>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label noEditable" for='{{ $idCostoPuntosInput }}'>Costo unitario (máx. 60000 puntos):</label>
                        <input class="input-item" id='{{ $idCostoPuntosInput }}' maxlength="5"
                                   oninput="validateNumberRealTime(this)" placeholder="60000" disabled>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" for='{{ $idStockRecompensaDelete }}'>Stock (máx. 1000 unidades):</label>
                        <input class="input-item" id='{{ $idStockRecompensaDelete }}' maxlength="4"
                                   oninput="validateNumberRealTime(this)" disabled>
                    </div>
                    
                    <div class="form-group start">
                        <span class="noInline-alert-message" id="eliminarRecompensaMessageError">  </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEliminarRecompensa')">Cancelar</button>
                <button type="button" class="btn btn-primary delete" 
                        onclick="guardarModalEliminarRecompensa('modalEliminarRecompensa', 'formEliminarRecompensa')">Eliminar</button>
            </div>
        </div>
    </div>
</div>


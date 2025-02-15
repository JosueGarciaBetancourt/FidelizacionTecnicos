<div class="modal first"  id="modalRestaurarRecompensa">
    <div class="modal-dialog" id="modalRestaurarRecompensa-dialog">
        <div class="modal-content" id="modalRestaurarRecompensa-content">
            <div class="modal-header">
                <h5 class="modal-title">Habilitar recompensa</h5>
                <button class="close noUserSelect" onclick="closeModal('modalRestaurarRecompensa')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyRestaurarRecompensa">
                <form id="formRestaurarRecompensa" action="{{ route('recompensas.restore') }}" method="POST">
                    @csrf
                    <!-- Variables globales -->
                    @php
                        $recompensasEliminadasDB = $recompensasEliminadas;
                        $dbFieldsNameArray = ['nombre_TipoRecompensa', 'descripcionRecompensa', 'costoPuntos_Recompensa'];
                        $idInput = 'recompensaInputRestaurar';
                        $idOptions = 'recompensaRestaurarOptions';
                        $idMessageError = 'searchRestaurarRecompensaError';
                        $someHiddenIdInputsArray = ['idRestaurarRecompensaInput'];
                        $idCostoPuntosInput = 'costoPuntosInputRestaurar'; //El valor se debe modificar también en modalRestaurarRecompensa.js
                        $idStockRecompensa = 'stockRecompensaInputRestaurar'; 
                        $idTipoRecompensaInputRestaurar = 'tipoRecompensaInputRestaurar';
                        $idDescripcionRecompensaInputRestaurar = 'descripcionRecompensaInputRestaurar';
                        $otherInputsArray = [$idTipoRecompensaInputRestaurar , 'descripcionRecompensaInputRestaurar', $idCostoPuntosInput];
                        $generalErrorMessage = 'restaurarRecompensaGeneralMessageError';
                        $searchDBField = 'idRecompensa';
                    @endphp
                    <input type="hidden" id='{{ $someHiddenIdInputsArray[0] }}' maxlength="9" name="idRecompensa">
                   
                    <div class="form-group start paddingY" id="idH5RestaurarRecompensaModalContainer">
                        <h5>*Solo puede habilitar recompensas previamente inhabilitadas.</h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" for="recompensaRestaurarSelect">Recompensa:</label>
                        <div class="input-select" id="recompensaRestaurarSelect">
                            <input class="input-select-item" type="text" id='{{ $idInput }}' maxlength="100" placeholder="Código | Descripción" autocomplete="off"
                                oninput="filterOptions('{{ $idInput }}', '{{ $idOptions }}'),
                                        validateValueOnRealTime(this, '{{ $idOptions }}', '{{ $idMessageError }}', 
                                        {{ json_encode($someHiddenIdInputsArray) }}, {{ json_encode($otherInputsArray) }}, 
                                        {{ json_encode($recompensasEliminadasDB) }}, '{{ $searchDBField }}', {{ json_encode($dbFieldsNameArray) }})"

                                onclick="toggleOptions('{{ $idInput }}', '{{ $idOptions }}')">
                            <ul class="select-items" id='{{ $idOptions }}'>
                                @if (count($recompensasEliminadasDB) > 0)
                                    @foreach ($recompensasEliminadasDB as $recompensa)
                                        @php
                                            $idRecompensa = htmlspecialchars($recompensa->idRecompensa, ENT_QUOTES, 'UTF-8');
                                            $descripcionRecompensa = htmlspecialchars($recompensa->descripcionRecompensa, ENT_QUOTES, 'UTF-8');
                                            $costoPuntos = htmlspecialchars($recompensa->costoPuntos_Recompensa, ENT_QUOTES, 'UTF-8');
                                            $stockRecompensa = htmlspecialchars($recompensa->stock_Recompensa, ENT_QUOTES, 'UTF-8');
                                            $tipoRecompensa = htmlspecialchars($recompensa->nombre_TipoRecompensa, ENT_QUOTES, 'UTF-8');
                                            $value = $idRecompensa . " | " . $descripcionRecompensa;
                                        @endphp
                                
                                    <li onclick="selectOptionRestaurarRecompensa('{{ $value }}', '{{ $idRecompensa }}', '{{ $descripcionRecompensa }}', 
                                            '{{ $costoPuntos }}', '{{ $stockRecompensa }}', '{{ $tipoRecompensa }}', '{{ $idInput }}', '{{ $idOptions }}',
                                            {{ json_encode($someHiddenIdInputsArray) }})">
                                            {{ $value }}
                                        </li>
                                    @endforeach
                                @else
                                    <li>
                                        No hay recompensas inhabilitadas aún
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <span class="noInline-alert-message" id='{{ $idMessageError }}'>No se encontró la recompensa buscada</span>      
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" id="tipoRecompensaLabelRestaurar" for="tipoRecompensaInput">Tipo:</label>
                        <x-onlySelect-input 
                            :idInput="$idTipoRecompensaInputRestaurar"
                            :inputClassName="'onlySelectInput long noHandCursor'"
                            :placeholder="'Tipo de recompensa'"
                            {{-- :name="'tipoRecompensa'" --}}
                            :options="['Accesorio', 'EPP', 'Herramienta']"
                            :disabled="true"
                            :spanClassName="'noUserSelect noHandCursor'"
                            :focusBorder="'noFocusBorder'"
                        />
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" for="idRecompensaDescripcion">Descripción:</label>
                        <textarea class="textarea normal" id="descripcionRecompensaInputRestaurar" placeholder="Breve descripción" disabled></textarea>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label noEditable" for='{{ $idCostoPuntosInput }}'>Costo unitario (máx. 60000 puntos):</label>
                        <input class="input-item" id='{{ $idCostoPuntosInput }}' maxlength="5"
                                   oninput="validateNumberRealTime(this)" placeholder="60000" disabled>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label noEditable" for='{{ $idStockRecompensa }}'>Stock (máx. 1000 unidades):</label>
                        <input class="input-item" id='{{ $idStockRecompensa }}' maxlength="4"
                                   oninput="validateNumberRealTime(this)" disabled>
                    </div>
                    
                    <div class="form-group start">
                        <span class="noInline-alert-message" id='{{ $generalErrorMessage }}'>  </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRestaurarRecompensa')">Cancelar</button>
                <button type="button" class="btn btn-primary recover" 
                        onclick="guardarModalRestaurarRecompensa('modalRestaurarRecompensa', 'formRestaurarRecompensa')">Habilitar</button>
            </div>
        </div>
    </div>
</div>


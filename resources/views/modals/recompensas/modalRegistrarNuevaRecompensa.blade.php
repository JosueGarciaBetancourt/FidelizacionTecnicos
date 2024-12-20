<div class="modal first"  id="modalRegistrarNuevaRecompensa">
    <div class="modal-dialog" id="modalRegistrarNuevaRecompensa-dialog">
        <div class="modal-content" id="modalRegistrarNuevaRecompensa-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear nueva recompensa</h5>
                <button class="close noUserSelect" onclick="closeModal('modalRegistrarNuevaRecompensa')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyRegistrarNuevaRecompensa">
                <form id="formRegistrarNuevaRecompensa" action="{{ route('recompensas.store') }}" method="POST">
                    @csrf
                   
                    @php
                        $recompensasDB = $recompensas;
                        $nombresTiposRecompensasDB = $nombresTiposRecompensas;
                    @endphp

                    <div class="form-group gap">
                        <label class="primary-label" id="codigoRecompensaLabel" for="codigoRecompensaInput">Código de recompensa:</label>
                        <input class="input-item readonly" id="codigoRecompensaInput" name="idRecompensa" 
                               maxlength="13" value="{{ $idNuevaRecompensa }}" disabled>
                    </div>
                    
                    <div class="form-group gap">
                        <label class="primary-label" id="tipoRecompensaLabel" for="tipoRecompensaInput">Tipo:</label>
                        <x-onlySelect-input 
                            :idInput="'tipoRecompensaInput'"
                            :inputClassName="'onlySelectInput long'"
                            :placeholder="'Seleccionar tipo de recompensa'"
                            :name="'tipoRecompensa'"
                            :options="$nombresTiposRecompensasDB"
                        />
                    </div>
                    <div class="form-group gap">
                        <label class="primary-label" id="descripcionLabel" for="descripcionRecompensaTextarea">Descripción:</label>
                        <textarea class="textarea normal" maxlength="100" id="descripcionRecompensaTextarea" name="descripcionRecompensa" placeholder="Ingresar una breve descripción"></textarea>
                    </div>
                
                    <div class="form-group gap">
                        <label class="primary-label" id="costoUnitarioLabel" for="costoUnitarioInput">Costo unitario (máx. 60000 puntos):</label>
                        <input class="input-item" id="costoUnitarioInput" name="costoPuntos_Recompensa" maxlength="5"
                                   oninput="validateNumberWithMaxLimitRealTime(this, 60000)" placeholder="60000">
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" id="stockRecompensaLabel" for="stockRecompensaInput">Stock (máx. 1000 unidades):</label>
                        <input class="input-item" id="stockRecompensaInput" name="stock_Recompensa" maxlength="4"
                                   oninput="validateNumberWithMaxLimitRealTime(this, 1000)" placeholder="1000">
                    </div>

                    <div class="form-group start">
                        <span class="inline-alert-message" id="registrarRecompensaMessageError"> multiMessageError </span>      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRegistrarNuevaRecompensa')">Cancelar</button>
                <button type="button" class="btn btn-primary" 
                        onclick="guardarModalRegistrarNuevaRecompensa('modalRegistrarNuevaRecompensa', 'formRegistrarNuevaRecompensa',
                                                                        {{ json_encode($recompensasDB) }})">Guardar</button>
            </div>
        </div>
    </div>
</div>

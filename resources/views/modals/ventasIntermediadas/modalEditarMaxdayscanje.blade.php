<div class="modal first modalEditarMaxdayscanje" id="modalEditarMaxdayscanje">
    <div class="modal-dialog modalEditarMaxdayscanje">
        <div class="modal-content modalEditarMaxdayscanje">
            <div class="modal-header">
                <h5 class="modal-title">Editar días máximos de canje</h5>
                <button class="close noUserSelect" onclick="closeModal('modalEditarMaxdayscanje')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyEditarMaxdayscanje">
                <form id="formEditarMaxdayscanje" action="{{ route('ventasIntermediadas.updateMaxdayscanje') }}" method="POST">
                    @method('PUT')
                    @csrf

                    @php 
                        $maxdayscanje = $maxdaysCanje;
                        $maxdayscanjeInput = 'editarMaxdayscanjeInput';
                        $editarMaxdayscanjeError = 'editarMaxdayscanjeError';
                    @endphp

                    <div class="form-group start paddingY" id="idH5EditarMaxdayscanjeContainer">
                        <h5>Los días máximos de canje ó también días máximos de registro de venta determinan el tiempo límite para que una venta intermediada pase al estado
                            <span>Tiempo Agotado</span> y el tiempo límite para que un técnico pueda registrarla.
                        </h5>
                    </div>

                    <div class="form-group gap">
                        <label class="primary-label" id="maxdayscanjeLabel" for="{{ $maxdayscanjeInput }}">Valor actual: </label>
                        <input class="input-item" type="text" id="{{ $maxdayscanjeInput }}" oninput="validateRealTimeInputLength(this, 2), validateNumberWithMaxLimitRealTime(this, 90)"
                            placeholder="90" name="maxdaysCanje" value="{{ $maxdayscanje }}" required>
                    </div>

                    <span class="inline-alert-message" id="{{ $editarMaxdayscanjeError }}"> No puedes enviar un valor vacío</span>  
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditarMaxdayscanje')">Cancelar</button>
                <button type="button" class="btn btn-primary" 
                        onclick="guardarModalEditarMaxdayscanje('modalEditarMaxdayscanje', 'formEditarMaxdayscanje')">Guardar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal second modalAgregarNuevoTecnico" id="modalAgregarNuevoTecnico">
    <div class="modal-dialog modalAgregarNuevoTecnico">
        <div class="modal-content modalAgregarNuevoTecnico">
            <div class="modal-header">
                <h5 class="modal-title">Registrar nuevo técnico</h5>
                <button class="close noUserSelect" onclick="closeModal('modalAgregarNuevoTecnico')">&times;</button>
            </div>
            <div class="modal-body" id="idModalBodyAgregarNuevoTecnico">
                <form id="formAgregarNuevoTecnico" action="{{ route('tecnicos.store') }}" method="POST">
                    @csrf

                    @php 
                        $idsNombresOficiosBD = $idsNombresOficios;
                    @endphp
                  
                    <div class="form-group">
                        <label class="primary-label marginX" id="dniLabel" for="dniInput">DNI:</label>
                        <input class="input-item" type="number" id="dniInput" placeholder="12345678" 
                               oninput="validateRealTimeInputLength(this, 8), validateNumberRealTime(this)" name="idTecnico">
                        <label class="primary-label marginX" id="nameLabel"  for="nameInput">Nombre:</label>
                        <input class="input-item" type="text" id="nameInput" placeholder="Ingresar nombre" name="nombreTecnico"
                               oninput="validateRealTimeInputLength(this, 60)">
                    </div>
                    
                    <div class="form-group">
                        <label class="primary-label marginX" id="phoneLabel" for="phoneInput">Celular:</label>
                        <input class="input-item" type="number" id="phoneInput" placeholder="999888777"
                               oninput="validateRealTimeInputLength(this, 9), validateNumberRealTime(this)" name="celularTecnico">
                        <label class="primary-label marginX" id="bornDateLabel" for="bornDateInput">Fecha de nacimiento:</label>
                        <input class="input-item" type="date" id="bornDateInput" name="fechaNacimiento_Tecnico">
                    </div>
                    
                    {{--  <div class="form-group">
                        <label class="primary-label marginX" id="oficioLabel" for="oficioInput">Oficio:</label>
                        <x-onlySelect-input 
                            :idSelect="'oficioSelect'"
                            :inputClassName="'onlySelectInput'"
                            :idInput="'oficioInput'"
                            :idOptions="'oficioOptionsCreate'"
                            :placeholder="'Seleccionar oficio'"
                            :options="$idsNombresOficiosBD"
                            :onSelectFunction="'selectOptionOficio'"
                            :onSpanClickFunction="'cleanHiddenOficiosInput'"
                            :spanClassName="'noUserSelect'"
                            :emptyDataMessage="'No hay oficios registrados aún'"
                        />
                        <input type="text" id="idsOficioArrayInput" name="idOficioArray">
                        <span class="inline-alert-message" id="dateMessageError"> dateMessageError </span>      
                    </div> --}}
                    
                    <div class="form-group-multiSelectDropdown">
                        <label class="primary-label marginX" id="oficioLabel" for="oficioInput">Oficio:</label>
                        <x-multiSelectDropdown
                            :options="$idsNombresOficiosBD"
                            :empyDataMessage="'No hay oficios registrados aún'"
                        />
                    </div>
                    
                    <div class="form-group start">
                        <span class="inline-alert-message" id="multiMessageError"> multiMessageError </span>      
                    </div>
                  
                    <input type="hidden" name="origin" id="origin">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalAgregarNuevoTecnico')">Cancelar</button>
                
                <button type="button" class="btn btn-primary"
                        onclick="guardarModalAgregarNuevoTecnico('modalAgregarNuevoTecnico', 'formAgregarNuevoTecnico')">Guardar</button>
            </div>
        </div>
    </div>
</div>


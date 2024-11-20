let codigoTipoRecompensaInputEdit = document.getElementById('codigoTipoRecompensaInputEdit');
let idNumberTipoRecompensaInputEdit = document.getElementById('idNumberTipoRecompensaEdit');
let nombreTipoRecompensaInputEdit = document.getElementById('nombreTipoRecompensaInputEdit');
let searchEditTipoRecompensaMessageError = document.getElementById('searchEditTipoRecompensaError');
let generalEditTipoRecompensaError = document.getElementById('generalEditTipoRecompensaError');

let formEditTipoRecompensaArray = [
    codigoTipoRecompensaInputEdit,
    nombreTipoRecompensaInputEdit,
];

let mensajeCombinadoEditOficio = "";

function selectOptionEditTipoRecompensa(value, idNumberTipoRecompensa, nombreTipoRecompensa, idInput, idOptions, someHiddenIdInputsArray) {
    // Escapar caracteres especiales en la descripción
    function sanitizeString(str) {
        if (typeof str !== 'string') return str;
        return str
            .replace(/&/g, '&amp;')  // Reemplazar & por &amp;
            .replace(/</g, '&lt;')   // Reemplazar < por &lt;
            .replace(/>/g, '&gt;')   // Reemplazar > por &gt;
            .replace(/"/g, '&quot;') // Reemplazar " por &quot;
            .replace(/'/g, '&#39;')  // Reemplazar ' por &#39;
            .replace(/\n/g, '\\n')   // Reemplazar saltos de línea por \n
            .replace(/\r/g, '\\r');  // Reemplazar retornos de carro por \r
    }

    // Sanitizar solo la descripción
    const sanitizednombreTipoRecompensa = sanitizeString(nombreTipoRecompensa);

    // Colocar en el input la opción seleccionada 
    selectOption(value, idInput, idOptions); 

    // Actualizar los demás campos del formulario
    if (sanitizednombreTipoRecompensa) {
        nombreTipoRecompensaInputEdit.value = sanitizednombreTipoRecompensa;
        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberTipoRecompensa;
        searchEditTipoRecompensaMessageError.classList.remove("shown");
    } else {
        nombreTipoRecompensaInputEdit.value = "";
    }
}

function validarCamposVaciosFormularioTipoRecompensaEdit() {
    let allFilled = true;
    formEditTipoRecompensaArray.forEach(input => {
        if (!input.value.trim()) {
            allFilled = false;
        }
    });
    return allFilled;
}

function isTipoRecompensaEditDuplicado(tiposRecompensasDB) {
    const nombre = nombreTipoRecompensaInputEdit.value.trim(); 
    const tipoRecompensaExistente = tiposRecompensasDB.find(tipoRecompensa => tipoRecompensa.nombre_TipoRecompensa === nombre);
    
    // Retornar true si se encuentra una coincidencia, false en caso contrario
    return !!tipoRecompensaExistente; 
}

function isTipoAsociadoRecompensaEdit(recompensasDB) {
    const idNumberTipoRecompensa= idNumberTipoRecompensaInputEdit.value.trim(); 
    const recompensaAsociadaExistente = recompensasDB.find(recompensa => recompensa.idTipoRecompensa == idNumberTipoRecompensa);
    // Retornar true si se encuentra una coincidencia, false en caso contrario
    return !!recompensaAsociadaExistente; 
}

function validarCamposCorrectosTipoRecompensaEdit(tiposRecompensasDB, recompensasDB) {
    mensajeCombinadoEditOficio = "";
    var returnError = false;

    if (isTipoRecompensaEditDuplicado(tiposRecompensasDB)) {
        mensajeCombinadoEditOficio += "El nombre de este tipo de recompensa ya ha sido registrado anteriormente. ";
        returnError = true;
    }
    
    if (isTipoAsociadoRecompensaEdit(recompensasDB)) {
        mensajeCombinadoEditOficio += `El tipo de recompensa con código ${codigoTipoRecompensaInputEdit.value} ya tiene recompensas asociadas, no puede editarlo.`;
        returnError = true;
    }

    if (returnError) {
        return false;
    }

    generalEditTipoRecompensaError.classList.remove("shown");
    return true;
}

function guardarModalEditarTipoRecompensa(idModal, idForm, tiposRecompensasDB, recompensasDB) {
    if (validarCamposVaciosFormularioTipoRecompensaEdit()) {
        if (validarCamposCorrectosTipoRecompensaEdit(tiposRecompensasDB, recompensasDB)) {
            generalEditTipoRecompensaError.classList.remove("shown");
            guardarModal(idModal, idForm);	
        } else {
            generalEditTipoRecompensaError.textContent = mensajeCombinadoEditOficio;
            generalEditTipoRecompensaError.classList.add("shown");
        }
	} else {
        generalEditTipoRecompensaError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalEditTipoRecompensaError.classList.add("shown");
    }
}
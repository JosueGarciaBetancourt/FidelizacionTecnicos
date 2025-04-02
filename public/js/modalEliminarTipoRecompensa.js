let codigoTipoRecompensaInputDelete = document.getElementById('codigoTipoRecompensaInputDelete');
let idNumberTipoRecompensaInputDelete = document.getElementById('idNumberTipoRecompensaDelete');
let nombreTipoRecompensaInputDelete = document.getElementById('nombreTipoRecompensaInputDelete');
let searchDeleteTipoRecompensaMessageError = document.getElementById('searchDeleteTipoRecompensaError');
let generalDeleteTipoRecompensaError = document.getElementById('generalDeleteTipoRecompensaError');

let formDeleteTipoRecompensaArray = [
    codigoTipoRecompensaInputDelete,
    nombreTipoRecompensaInputDelete,
];

let mensajeCombinadoDeleteOficio = "";

function selectOptionEliminarTipoRecompensa(value, idNumberTipoRecompensa, nombreTipoRecompensa, idInput, idOptions, someHiddenIdInputsArray) {
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
        nombreTipoRecompensaInputDelete.value = sanitizednombreTipoRecompensa;
        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberTipoRecompensa;
        searchDeleteTipoRecompensaMessageError.classList.remove("shown");
    } else {
        nombreTipoRecompensaInputDelete.value = "";
    }
}
function validarCamposVaciosFormularioTipoRecompensaDelete() {
    let allFilled = true;
    formDeleteTipoRecompensaArray.forEach(input => {
        if (!input.value.trim()) {
            allFilled = false;
        }
    });
    return allFilled;
}

function isTipoAsociadoRecompensaDelete(recompensasDB) {
    const idNumberTipoRecompensa= idNumberTipoRecompensaInputDelete.value.trim(); 
    const recompensaAsociadaExistente = recompensasDB.find(recompensa => recompensa.idTipoRecompensa == idNumberTipoRecompensa);
    // Retornar true si se encuentra una coincidencia, false en caso contrario
    return !!recompensaAsociadaExistente; 
}

function validarCamposCorrectosTipoRecompensaDelete(recompensasDB) {
    mensajeCombinadoDeleteOficio = "";
    var returnError = false;

    if (isTipoAsociadoRecompensaDelete(recompensasDB)) {
        // mensajeCombinadoDeleteOficio += `El tipo de recompensa con código ${codigoTipoRecompensaInputDelete.value} ya tiene recompensas asociadas, no puede eliminarlo.`;
        returnError = true;
        const msg = `El Tipo de Recompensa con código ${codigoTipoRecompensaInputDelete.value} tiene recompensas asociadas, no puede eliminarlo`;
        openErrorModal("errorModalTipoRecompensa", msg);
    }

    if (returnError) {
        return false;
    }

    generalEditTipoRecompensaError.classList.remove("shown");
    return true;
}

function guardarModalEliminarTipoRecompensa(idModal, idForm, recompensasDB) {
    if (validarCamposVaciosFormularioTipoRecompensaDelete()) {
        if (validarCamposCorrectosTipoRecompensaDelete(recompensasDB)) {
            generalDeleteTipoRecompensaError.classList.remove("shown");
            guardarModal(idModal, idForm);	
        } else {
            generalDeleteTipoRecompensaError.textContent = mensajeCombinadoDeleteOficio;
            generalDeleteTipoRecompensaError.classList.add("shown");
        }
    } else {
        generalDeleteTipoRecompensaError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalDeleteTipoRecompensaError.classList.add("shown");
    }
}
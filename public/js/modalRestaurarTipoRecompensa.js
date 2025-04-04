let codigoTipoRecompensaInputRestore = document.getElementById('codigoTipoRecompensaInputRestore');
let idNumberTipoRecompensaInputRestore = document.getElementById('idNumberTipoRecompensaRestore');
let nombreTipoRecompensaInputRestore = document.getElementById('nombreTipoRecompensaInputRestore');
let descripcionTipoRecompensaInputRestore = document.getElementById('descripcionTipoRecompensaInputRestore');
let colorTextoTipoRecompensaInputRestore = document.getElementById('colorTextoTipoRecompensaInputRestore');
let colorFondoTipoRecompensaInputRestore = document.getElementById('colorFondoTipoRecompensaInputRestore');
let previewColorSpanTipoRecompensaRestore = document.getElementById('previewColorSpanTipoRecompensaRestore');
let searchRestoreTipoRecompensaMessageError = document.getElementById('searchRestoreTipoRecompensaError');
let generalRestoreTipoRecompensaError = document.getElementById('generalRestoreTipoRecompensaError');

let formRestoreTipoRecompensaArray = [
    codigoTipoRecompensaInputRestore,
    nombreTipoRecompensaInputRestore,
];

let mensajeCombinadoRestoreOficio = "";

function selectOptionRestaurarTipoRecompensa(value, idNumberTipoRecompensa, nombreTipoRecompensa, descripcionTipoRecompensa,
                                            colorTextoTipoRecompensa, colorFondoTipoRecompensa, idInput, idOptions, someHiddenIdInputsArray) {
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

    const sanitizedNombreTipoRecompensa = sanitizeString(nombreTipoRecompensa);
    const sanitizedDescripcionTipoRecompensa = sanitizeString(descripcionTipoRecompensa);

    // Colocar en el input la opción seleccionada 
    selectOption(value, idInput, idOptions); 

    // Actualizar los demás campos del formulario
    if (sanitizedNombreTipoRecompensa  && sanitizedDescripcionTipoRecompensa) {
        nombreTipoRecompensaInputRestore.value = sanitizedNombreTipoRecompensa;
        descripcionTipoRecompensaInputRestore.value = sanitizedDescripcionTipoRecompensa;
        updateColorsInput(colorTextoTipoRecompensaInputRestore, colorFondoTipoRecompensaInputRestore, previewColorSpanTipoRecompensaRestore,
                        colorTextoTipoRecompensa, colorFondoTipoRecompensa, sanitizedNombreTipoRecompensa);

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberTipoRecompensa;
        searchRestoreTipoRecompensaMessageError.classList.remove("shown");
    } else {
        nombreTipoRecompensaInputRestore.value = "";
        descripcionRangoInputRestore.value = "";
        updateColorsInput(colorTextoTipoRecompensaInputRestore, colorFondoTipoRecompensaInputRestore, previewColorSpanTipoRecompensaRestore);
    }
}

function validateValueOnRealTimeTipoRecompensaRestore(input, idOptions, idSearchMessageError, someHiddenIdInputsArray, otherInputsArray, colorInputsArray, itemsDB, 
                                                    searchField, dbFieldsNameArray, dbColorFieldsNameArray, idGeneralMessageError) {

    validateValueOnRealTimeIDInteger(input, idOptions, idSearchMessageError, someHiddenIdInputsArray, otherInputsArray, itemsDB, 
                                    searchField, dbFieldsNameArray, idGeneralMessageError);

    if (!fillColorInputOnRealTimeIDIntegerNameApart(input, nombreTipoRecompensaInputRestore, idOptions, colorInputsArray, dbColorFieldsNameArray, searchField, itemsDB, previewColorSpanTipoRecompensaRestore)) {
        updateColorsInput(colorTextoTipoRecompensaInputRestore, colorFondoTipoRecompensaInputRestore, previewColorSpanTipoRecompensaRestore);
    }
}

function validarCamposVaciosFormularioTipoRecompensaRestore() {
    let allFilled = true;
    formRestoreTipoRecompensaArray.forEach(input => {
        if (!input.value.trim()) {
            allFilled = false;
        }
    });
    return allFilled;
}

function isTipoAsociadoRecompensaRestore(recompensasDB) {
    const idNumberTipoRecompensa= idNumberTipoRecompensaInputRestore.value.trim(); 
    const recompensaAsociadaExistente = recompensasDB.find(recompensa => recompensa.idTipoRecompensa == idNumberTipoRecompensa);
    // Retornar true si se encuentra una coincidencia, false en caso contrario
    return !!recompensaAsociadaExistente; 
}

function validarCamposCorrectosTipoRecompensaRestore(recompensasDB) {
    mensajeCombinadoRestoreOficio = "";
    var returnError = false;

    if (isTipoAsociadoRecompensaRestore(recompensasDB)) {
        // mensajeCombinadoRestoreOficio += `El tipo de recompensa con código ${codigoTipoRecompensaInputRestore.value} ya tiene recompensas asociadas, no puede Restaurarlo.`;
        returnError = true;
        const msg = `El Tipo de Recompensa con código ${codigoTipoRecompensaInputRestore.value} tiene recompensas asociadas, no puede Restaurarlo`;
        openErrorModal("errorModalTipoRecompensa", msg);
    }

    if (returnError) {
        return false;
    }

    generalRestoreTipoRecompensaError.classList.remove("shown");
    return true;
}

function guardarModalRestaurarTipoRecompensa(idModal, idForm, recompensasDB) {
    if (validarCamposVaciosFormularioTipoRecompensaRestore()) {
        if (validarCamposCorrectosTipoRecompensaRestore(recompensasDB)) {
            generalRestoreTipoRecompensaError.classList.remove("shown");
            guardarModal(idModal, idForm);	
        } else {
            generalRestoreTipoRecompensaError.textContent = mensajeCombinadoRestoreOficio;
            generalRestoreTipoRecompensaError.classList.add("shown");
        }
    } else {
        generalRestoreTipoRecompensaError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalRestoreTipoRecompensaError.classList.add("shown");
    }
}
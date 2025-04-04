let codigoTipoRecompensaInputDelete = document.getElementById('codigoTipoRecompensaInputDelete');
let idNumberTipoRecompensaInputDelete = document.getElementById('idNumberTipoRecompensaDelete');
let nombreTipoRecompensaInputDelete = document.getElementById('nombreTipoRecompensaInputDelete');
let descripcionTipoRecompensaInputDelete = document.getElementById('descripcionTipoRecompensaInputDelete');
let colorTextoTipoRecompensaInputDelete = document.getElementById('colorTextoTipoRecompensaInputDelete');
let colorFondoTipoRecompensaInputDelete = document.getElementById('colorFondoTipoRecompensaInputDelete');
let previewColorSpanTipoRecompensaDelete = document.getElementById('previewColorSpanTipoRecompensaDelete');
let searchDeleteTipoRecompensaMessageError = document.getElementById('searchDeleteTipoRecompensaError');
let generalDeleteTipoRecompensaError = document.getElementById('generalDeleteTipoRecompensaError');

let formDeleteTipoRecompensaArray = [
    codigoTipoRecompensaInputDelete,
    nombreTipoRecompensaInputDelete,
];

let mensajeCombinadoDeleteOficio = "";

function selectOptionEliminarTipoRecompensa(value, idNumberTipoRecompensa, nombreTipoRecompensa, descripcionTipoRecompensa,
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
        nombreTipoRecompensaInputDelete.value = sanitizedNombreTipoRecompensa;
        descripcionTipoRecompensaInputDelete.value = sanitizedDescripcionTipoRecompensa;
        updateColorsInput(colorTextoTipoRecompensaInputDelete, colorFondoTipoRecompensaInputDelete, previewColorSpanTipoRecompensaDelete,
                        colorTextoTipoRecompensa, colorFondoTipoRecompensa, sanitizedNombreTipoRecompensa);

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberTipoRecompensa;
        searchDeleteTipoRecompensaMessageError.classList.remove("shown");
    } else {
        nombreTipoRecompensaInputDelete.value = "";
        descripcionRangoInputDelete.value = "";
        updateColorsInput(colorTextoTipoRecompensaInputDelete, colorFondoTipoRecompensaInputDelete, previewColorSpanTipoRecompensaDelete);
    }
}

function validateValueOnRealTimeTipoRecompensaDelete(input, idOptions, idSearchMessageError, someHiddenIdInputsArray, otherInputsArray, colorInputsArray, itemsDB, 
                                                    searchField, dbFieldsNameArray, dbColorFieldsNameArray, idGeneralMessageError) {

    validateValueOnRealTimeIDInteger(input, idOptions, idSearchMessageError, someHiddenIdInputsArray, otherInputsArray, itemsDB, 
                                    searchField, dbFieldsNameArray, idGeneralMessageError);

    if (!fillColorInputOnRealTimeIDIntegerNameApart(input, nombreTipoRecompensaInputDelete, idOptions, colorInputsArray, dbColorFieldsNameArray, searchField, itemsDB, previewColorSpanTipoRecompensaDelete)) {
        updateColorsInput(colorTextoTipoRecompensaInputDelete, colorFondoTipoRecompensaInputDelete, previewColorSpanTipoRecompensaDelete);
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

    generalDeleteTipoRecompensaError.classList.remove("shown");
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
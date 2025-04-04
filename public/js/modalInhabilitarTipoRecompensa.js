let codigoTipoRecompensaInputDisable = document.getElementById('codigoTipoRecompensaInputDisable');
let idNumberTipoRecompensaInputDisable = document.getElementById('idNumberTipoRecompensaDisable');
let nombreTipoRecompensaInputDisable = document.getElementById('nombreTipoRecompensaInputDisable');
let descripcionTipoRecompensaInputDisable = document.getElementById('descripcionTipoRecompensaInputDisable');
let colorTextoTipoRecompensaInputDisable = document.getElementById('colorTextoTipoRecompensaInputDisable');
let colorFondoTipoRecompensaInputDisable = document.getElementById('colorFondoTipoRecompensaInputDisable');
let previewColorSpanTipoRecompensaDisable = document.getElementById('previewColorSpanTipoRecompensaDisable');
let searchDisableTipoRecompensaMessageError = document.getElementById('searchDisableTipoRecompensaError');
let generalDisableTipoRecompensaError = document.getElementById('generalDisableTipoRecompensaError');

let formDisableTipoRecompensaArray = [
    codigoTipoRecompensaInputDisable,
    nombreTipoRecompensaInputDisable,
];

let mensajeCombinadoDisableOficio = "";

function selectOptionInhabilitarTipoRecompensa(value, idNumberTipoRecompensa, nombreTipoRecompensa, descripcionTipoRecompensa,
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
        nombreTipoRecompensaInputDisable.value = sanitizedNombreTipoRecompensa;
        descripcionTipoRecompensaInputDisable.value = sanitizedDescripcionTipoRecompensa;
        updateColorsInput(colorTextoTipoRecompensaInputDisable, colorFondoTipoRecompensaInputDisable, previewColorSpanTipoRecompensaDisable,
                        colorTextoTipoRecompensa, colorFondoTipoRecompensa, sanitizedNombreTipoRecompensa);

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberTipoRecompensa;
        searchDisableTipoRecompensaMessageError.classList.remove("shown");
    } else {
        nombreTipoRecompensaInputDisable.value = "";
        descripcionRangoInputDisable.value = "";
        updateColorsInput(colorTextoTipoRecompensaInputDisable, colorFondoTipoRecompensaInputDisable, previewColorSpanTipoRecompensaDisable);
    }
}

function validateValueOnRealTimeTipoRecompensaDisable(input, idOptions, idSearchMessageError, someHiddenIdInputsArray, otherInputsArray, colorInputsArray, itemsDB, 
                                                    searchField, dbFieldsNameArray, dbColorFieldsNameArray, idGeneralMessageError) {

    validateValueOnRealTimeIDInteger(input, idOptions, idSearchMessageError, someHiddenIdInputsArray, otherInputsArray, itemsDB, 
                                    searchField, dbFieldsNameArray, idGeneralMessageError);

    if (!fillColorInputOnRealTimeIDIntegerNameApart(input, nombreTipoRecompensaInputDisable, idOptions, colorInputsArray, dbColorFieldsNameArray, searchField, itemsDB, previewColorSpanTipoRecompensaDisable)) {
        updateColorsInput(colorTextoTipoRecompensaInputDisable, colorFondoTipoRecompensaInputDisable, previewColorSpanTipoRecompensaDisable);
    }
}

function validarCamposVaciosFormularioTipoRecompensaDisable() {
    let allFilled = true;
    formDisableTipoRecompensaArray.forEach(input => {
        if (!input.value.trim()) {
            allFilled = false;
        }
    });
    return allFilled;
}

function isTipoAsociadoRecompensaDisable(recompensasDB) {
    const idNumberTipoRecompensa= idNumberTipoRecompensaInputDisable.value.trim(); 
    const recompensaAsociadaExistente = recompensasDB.find(recompensa => recompensa.idTipoRecompensa == idNumberTipoRecompensa);
    // Retornar true si se encuentra una coincidencia, false en caso contrario
    return !!recompensaAsociadaExistente; 
}

function validarCamposCorrectosTipoRecompensaDisable(recompensasDB) {
    mensajeCombinadoDisableOficio = "";
    var returnError = false;

    if (isTipoAsociadoRecompensaDisable(recompensasDB)) {
        // mensajeCombinadoDisableOficio += `El tipo de recompensa con código ${codigoTipoRecompensaInputDisable.value} ya tiene recompensas asociadas, no puede Inhabilitarlo.`;
        returnError = true;
        const msg = `El Tipo de Recompensa con código ${codigoTipoRecompensaInputDisable.value} tiene recompensas asociadas, no puede Inhabilitarlo`;
        openErrorModal("errorModalTipoRecompensa", msg);
    }

    if (returnError) {
        return false;
    }

    generalDisableTipoRecompensaError.classList.remove("shown");
    return true;
}

function guardarModalInhabilitarTipoRecompensa(idModal, idForm, recompensasDB) {
    if (validarCamposVaciosFormularioTipoRecompensaDisable()) {
        if (validarCamposCorrectosTipoRecompensaDisable(recompensasDB)) {
            generalDisableTipoRecompensaError.classList.remove("shown");
            guardarModal(idModal, idForm);	
        } else {
            generalDisableTipoRecompensaError.textContent = mensajeCombinadoDisableOficio;
            generalDisableTipoRecompensaError.classList.add("shown");
        }
    } else {
        generalDisableTipoRecompensaError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalDisableTipoRecompensaError.classList.add("shown");
    }
}
let idNumberRangoInputDisable = document.getElementById('idDisableRangoInput');
let descripcionRangoInputDisable = document.getElementById('descripcionRangoInputDisable');
let puntosMinimosRangoInputDisable = document.getElementById('puntosMinimosRangoInputDisable');
let searchMessageErrorRangoDisable = document.getElementById('searchDisableRangoError');
let generalDisableRangoError = document.getElementById('generalDisableRangoError');
let colorTextoRangoInputDisable = document.getElementById('colorTextoRangoInputDisable');
let colorFondoRangoInputDisable = document.getElementById('colorFondoRangoInputDisable');
let previewColorSpanDisable = document.getElementById('previewColorSpanDisable');

let formDisableRangoArray = [
    descripcionRangoInputDisable,
    puntosMinimosRangoInputDisable,
];

function selectOptionInhabilitarRango(value, idNumberRango, descripcionRango, puntosMinimosRango, colorTextoRango, colorFondoRango,
                                    idInput, idOptions, someHiddenIdInputsArray) {

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
    const sanitizedDescripcionRango = sanitizeString(descripcionRango);

    // Colocar en el input la opción seleccionada 
    selectOption(value, idInput, idOptions); 

    const name = value.split(' | ')[1];

    // Actualizar los demás campos del formulario
    if (descripcionRango && sanitizedDescripcionRango && puntosMinimosRango && name) {
        descripcionRangoInputDisable.value = descripcionRango;
        puntosMinimosRangoInputDisable.value = puntosMinimosRango;
        updateColorsInput(colorTextoRangoInputDisable, colorFondoRangoInputDisable, previewColorSpanDisable, colorTextoRango, colorFondoRango, name);

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberRango;
        searchMessageErrorRangoDisable.classList.remove("shown");
        generalDisableRangoError.classList.remove("shown");
    } else {
        descripcionRangoInputDisable.value = "";
        puntosMinimosRangoInputDisable.value = "";  
        updateColorsInput(colorTextoRangoInputDisable, colorFondoRangoInputDisable, previewColorSpanDisable);
    }
}

function validateValueOnRealTimeRangoDisable(input, idOptions, idSearchMessageError, someHiddenIdInputsArray, otherInputsArray, colorInputsArray, itemsDB, 
                                            searchField, dbFieldsNameArray, dbColorFieldsNameArray, idGeneralMessageError) {

    validateValueOnRealTimeIDInteger(input, idOptions, idSearchMessageError, someHiddenIdInputsArray, otherInputsArray, itemsDB, 
                                    searchField, dbFieldsNameArray, idGeneralMessageError);

    if (!fillColorInputOnRealTimeIDInteger(input, idOptions, colorInputsArray, dbColorFieldsNameArray, searchField, itemsDB, previewColorSpanDisable)) {
        updateColorsInput(colorTextoRangoInputDisable, colorFondoRangoInputDisable, previewColorSpanDisable);
    }
}

function validarCamposVaciosFormularioDisable() {
    let allFilled = true;
    formDisableRangoArray.forEach(input => {
        if (!input.value.trim()) {
            allFilled = false;
        }
    });
    return allFilled;
}

function guardarModalInhabilitarRango(idModal, idForm) {
    if (validarCamposVaciosFormularioDisable()) {
        generalDisableRangoError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        generalDisableRangoError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalDisableRangoError.classList.add("shown");
    }
}
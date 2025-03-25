let idNumberRangoInputDisable = document.getElementById('idDisableRangoInput');
let descripcionRangoInputDisable = document.getElementById('descripcionRangoInputDisable');
let puntosMinimosRangoInputDisable = document.getElementById('puntosMinimosRangoInputDisable');
let searchMessageErrorRangoDisable = document.getElementById('searchDisableRangoError');
let generalDisableRangoError = document.getElementById('generalDisableRangoError');

let formDisableRangoArray = [
    descripcionRangoInputDisable,
    puntosMinimosRangoInputDisable,
];

function selectOptionInhabilitarRango(value, idNumberRango, descripcionRango, puntosMinimosRango, idInput, idOptions, someHiddenIdInputsArray) {
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

    // Actualizar los demás campos del formulario
    if (descripcionRango && sanitizedDescripcionRango && puntosMinimosRango) {
        descripcionRangoInputDisable.value = descripcionRango;
        puntosMinimosRangoInputDisable.value = puntosMinimosRango;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberRango;
        searchMessageErrorRangoDisable.classList.remove("shown");
        generalDisableRangoError.classList.remove("shown");
    } else {
        descripcionRangoInputDisable.value = "";
        puntosMinimosRangoInputDisable.value = "";
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
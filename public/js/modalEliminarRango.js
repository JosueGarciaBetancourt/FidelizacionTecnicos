let idNumberRangoInputDelete = document.getElementById('idDeleteRangoInput');
let descripcionRangoInputDelete = document.getElementById('descripcionRangoInputDelete');
let puntosMinimosRangoInputDelete = document.getElementById('puntosMinimosRangoInputDelete');
let searchErrorRangoDelete = document.getElementById('searchDeleteRangoError');
let generalDeleteRangoError = document.getElementById('generalDeleteRangoError');

let formDeleteRangoArray = [
    descripcionRangoInputDelete,
    puntosMinimosRangoInputDelete,
];

function selectOptionEliminarRango(value, idNumberRango, descripcionRango, puntosMinimosRango, idInput, idOptions, someHiddenIdInputsArray) {
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
    if (descripcionRango && sanitizedDescripcionRango) {
        descripcionRangoInputDelete.value = descripcionRango;
        puntosMinimosRangoInputDelete.value = puntosMinimosRango;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberRango;
        searchErrorRangoDelete.classList.remove("shown");
        generalDeleteRangoError.classList.remove("shown");
    } else {
        descripcionRangoInputDelete.value = "";
        puntosMinimosRangoInputDelete.value = "";
    }
}

function validarCamposVaciosFormularioDelete() {
    let allFilled = true;
    formDeleteRangoArray.forEach(input => {
        if (!input.value.trim()) {
            allFilled = false;
        }
    });
    return allFilled;
}

function guardarModalEliminarRango(idModal, idForm) {
    if (validarCamposVaciosFormularioDelete()) {
        generalDeleteRangoError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        generalDeleteRangoError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalDeleteRangoError.classList.add("shown");
    }
}
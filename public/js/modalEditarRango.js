let codigoRangoInputEdit = document.getElementById('codigoRangoInputEdit');
let idNumberRangoInput = document.getElementById('idNumberRango');
let descripcionRangoInputEdit = document.getElementById('descripcionRangoInputEdit');
let puntosMinimosRangoInputEdit = document.getElementById('puntosMinimosRangoInputEdit');
let searchEditMessageError = document.getElementById('searchEditRangoError');
let generalEditRangoError = document.getElementById('generalEditRangoError');

let formEditRangoArray = [
    codigoRangoInputEdit,
    descripcionRangoInputEdit,
    puntosMinimosRangoInputEdit,
];

function selectOptionEditRango(value, idNumberRango, descripcionRango, puntosMinimosRango, idInput, idOptions, someHiddenIdInputsArray) {
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
    const sanitizedDescripcionRango = sanitizeString(descripcionRango);

    // Colocar en el input la opción seleccionada 
    selectOption(value, idInput, idOptions); 

    // Actualizar los demás campos del formulario
    if (sanitizedDescripcionRango) {
        descripcionRangoInputEdit.value = sanitizedDescripcionRango;
        puntosMinimosRangoInputEdit.value = puntosMinimosRango;
        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberRango;
        searchEditMessageError.classList.remove("shown");
    } else {
        descripcionRangoInputEdit.value = "";
        puntosMinimosRangoInputEdit.value = "";
    }
}

function validarCamposCorrectosRangoEdit() {
    mensajeCombinadoEditRango = "";
    var returnError = false;

    /*if (stockRecompensaInputEdit.value == 0) {
        mensajeCombinadoEditRango += " El stock no puede ser 0.";
        returnError = true;
	}*/
    
    if (returnError) {
        return false;
    }

    generalEditRangoError.classList.remove("shown");
    return true;
}

function validarCamposVaciosFormularioRangoEdit() {
    let allFilled = true;
    formEditRangoArray.forEach(input => {
        if (!input.value.trim()) {
            allFilled = false;
        }
    });
    return allFilled;
}

function guardarModalEditarRango(idModal, idForm) {
    if (validarCamposVaciosFormularioRangoEdit()) {
        if (validarCamposCorrectosRangoEdit()) {
            generalEditRangoError.classList.remove("shown");
            guardarModal(idModal, idForm);	
        } else {
            generalEditRangoError.textContent = mensajeCombinadoEditRango;
            generalEditRangoError.classList.add("shown");
        }
	} else {
        generalEditRangoError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalEditRangoError.classList.add("shown");
    }
}
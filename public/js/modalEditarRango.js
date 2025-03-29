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

function enableDisablePuntosMinimosRangoInput(value = null) {
    if (!value || value != "RAN-01 | Sin rango") {
        puntosMinimosRangoInputEdit.classList.remove('blocked');
        puntosMinimosRangoInputEdit.removeAttribute('readonly', true); 
        return;
    }   

    puntosMinimosRangoInputEdit.classList.add('blocked');
    puntosMinimosRangoInputEdit.setAttribute('readonly', true);
}

function selectOptionEditRango(value, idNumberRango, descripcionRango, puntosMinimosRango, colorTextoRango, colorFondoRango,
                            idInput, idOptions, someHiddenIdInputsArray) {
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
        colorTextoRangoInputEdit.value = colorTextoRango;
        colorFondoRangoInputEdit.value = colorFondoRango;

        enableDisablePuntosMinimosRangoInput(value);

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberRango;

        searchEditMessageError.classList.remove("shown");
        generalEditRangoError.classList.remove("shown");
    } else {
        descripcionRangoInputEdit.value = "";
        puntosMinimosRangoInputEdit.value = "";
        colorTextoRangoInputEdit.value = "#3206B0";
        colorFondoRangoInputEdit.value = "#DCD5F0";
    }
}

function validateValueOnRealTimeRangoEdit(input, idOptions, idSearchMessageError, someHiddenIdInputsArray, otherInputsArray, colorInputsArray, itemsDB, 
                                        searchField, dbFieldsNameArray, dbColorFieldsNameArray, idGeneralMessageError) {
    
    enableDisablePuntosMinimosRangoInput(input.value);      

    validateValueOnRealTimeIDInteger(input, idOptions, idSearchMessageError, someHiddenIdInputsArray, otherInputsArray, itemsDB, 
                                    searchField, dbFieldsNameArray, idGeneralMessageError);

    if (!fillColorInputOnRealTime(input, idOptions, colorInputsArray, dbColorFieldsNameArray, searchField, itemsDB)) {
        colorTextoRangoInputEdit.value = "#3206B0";
        colorFondoRangoInputEdit.value = "#DCD5F0";
    }
}

let mensajeCombinadoEditRango = "";

function validarCamposCorrectosRangoEdit(rangosDB) {
    var returnError = false;
    mensajeCombinadoEditRango = "";
    
    // Asignar valor de puntos mínimos del formulario al rango de rangosDB
    rangosDB.find(r => r.idRango === Number(idNumberRangoInput.value)).puntosMinimos_Rango = Number(puntosMinimosRangoInputEdit.value);

    const puntosMinimosRangoPlata = rangosDB.find(r => r.idRango === 2).puntosMinimos_Rango;
    const puntosMinimosRangoOro = rangosDB.find(r => r.idRango === 3).puntosMinimos_Rango;
    const puntosMinimosRangoBlack = rangosDB.find(r => r.idRango === 4).puntosMinimos_Rango;

    // Validar que los rangos sean Plata < Oro < Black
    if (puntosMinimosRangoPlata >= puntosMinimosRangoOro || puntosMinimosRangoPlata >= puntosMinimosRangoBlack || puntosMinimosRangoOro >= puntosMinimosRangoBlack) {
        mensajeCombinadoEditRango += "Los puntos mínimos son incorrectos, asegúrate de que cumpla la siguiente regla: Plata < Oro < Black.";
        returnError = true;
    }
    
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

function guardarModalEditarRango(idModal, idForm, rangosDB) {
    if (validarCamposVaciosFormularioRangoEdit()) {
        if (validarCamposCorrectosRangoEdit(rangosDB)) {
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
let codigoTipoRecompensaInputEdit = document.getElementById('codigoTipoRecompensaInputEdit');
let idNumberTipoRecompensaInputEdit = document.getElementById('idNumberTipoRecompensaEdit');
let nombreTipoRecompensaInputEdit = document.getElementById('nombreTipoRecompensaInputEdit');
let descripcionTipoRecompensaInputEdit = document.getElementById('descripcionTipoRecompensaInputEdit');
let searchEditTipoRecompensaMessageError = document.getElementById('searchEditTipoRecompensaError');
let generalEditTipoRecompensaError = document.getElementById('generalEditTipoRecompensaError');
let colorTextoTipoRecompensaInputEdit = document.getElementById('colorTextoTipoRecompensaInputEdit');
let colorFondoTipoRecompensaInputEdit = document.getElementById('colorFondoTipoRecompensaInputEdit');
let previewColorSpanTipoRecompensaEdit = document.getElementById('previewColorSpanTipoRecompensaEdit');

let formEditTipoRecompensaArray = [
    codigoTipoRecompensaInputEdit,
    nombreTipoRecompensaInputEdit,
];

let mensajeCombinadoEditTipoRecompensa = "";

function selectOptionEditTipoRecompensa(value, idNumberTipoRecompensa, nombreTipoRecompensa, descripcionTipoRecompensa,
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
    if (sanitizedNombreTipoRecompensa && sanitizedDescripcionTipoRecompensa) {
        nombreTipoRecompensaInputEdit.value = sanitizedNombreTipoRecompensa;
        descripcionTipoRecompensaInputEdit.value = sanitizedDescripcionTipoRecompensa;
        updateColorsInput(colorTextoTipoRecompensaInputEdit, colorFondoTipoRecompensaInputEdit, previewColorSpanTipoRecompensaEdit,
                        colorTextoTipoRecompensa, colorFondoTipoRecompensa, sanitizedNombreTipoRecompensa);

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberTipoRecompensa;
        searchEditTipoRecompensaMessageError.classList.remove("shown");
    } else {
        nombreTipoRecompensaInputEdit.value = "";
        descripcionRangoInputEdit.value = "";
        updateColorsInput(colorTextoTipoRecompensaInputEdit, colorFondoTipoRecompensaInputEdit, previewColorSpanTipoRecompensaEdit);
    }
}

function validateValueOnRealTimeTipoRecompensaEdit(input, idOptions, idSearchMessageError, someHiddenIdInputsArray, otherInputsArray, colorInputsArray, itemsDB, 
                                                searchField, dbFieldsNameArray, dbColorFieldsNameArray, idGeneralMessageError) {

    validateValueOnRealTimeIDInteger(input, idOptions, idSearchMessageError, someHiddenIdInputsArray, otherInputsArray, itemsDB, 
                                    searchField, dbFieldsNameArray, idGeneralMessageError);
    
    if (!fillColorInputOnRealTimeIDIntegerNameApart(input, nombreTipoRecompensaInputEdit, idOptions, colorInputsArray, dbColorFieldsNameArray, searchField, itemsDB, previewColorSpanTipoRecompensaEdit)) {
        updateColorsInput(colorTextoTipoRecompensaInputEdit, colorFondoTipoRecompensaInputEdit, previewColorSpanTipoRecompensaEdit);
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
    const id = idNumberTipoRecompensaInputEdit.value; 
    const name = nombreTipoRecompensaInputEdit.value.trim().toLowerCase(); 
    
    // Buscar la recompensa original (por ID)
    const tipoRecompensaOriginal = tiposRecompensasDB.find(tipo => tipo.idTipoRecompensa == id);

    // Buscar si el nuevo nombre ya existe en otra recompensa
    const tipoRecompensaNueva = tiposRecompensasDB.find(tipo => tipo.nombre_TipoRecompensa.toLowerCase() === name);

    // Si el nuevo nombre ya existe pero pertenece a la misma recompensa, no es duplicado
    if (tipoRecompensaNueva && tipoRecompensaNueva.idTipoRecompensa == id) {
        console.log("Actualizar solo la descripción o colores");
        return false;
    }

    // Si el nombre cambió y ya existe en otro tipo de recompensa, es un duplicado
    if (tipoRecompensaNueva && tipoRecompensaNueva.idTipoRecompensa != id) {
        console.log("El nombre se repite en otros tipos de recompensas");
        return true;
    }

    console.log("El nombre no se repite en otros tipos de recompensas");
    return false;
}

function isTipoAsociadoRecompensaEdit(tiposRecompensasDB, recompensasDB) {
    const id = idNumberTipoRecompensaInputEdit.value; 
    const name = nombreTipoRecompensaInputEdit.value.trim().toLowerCase(); 
    const tipoRecompensaNueva = tiposRecompensasDB.find(tipo => tipo.nombre_TipoRecompensa.toLowerCase() === name);
    const recompensaAsociadaExistente = recompensasDB.find(recompensa => recompensa.idTipoRecompensa == id);
  
    // Si existe una recompensa asociada y cambia el nombre (nuevo nombre no duplicado) entonces devolver true
    if (recompensaAsociadaExistente && !tipoRecompensaNueva) {
        return true;
    }

    return false;
}

function validarCamposCorrectosTipoRecompensaEdit(tiposRecompensasDB, recompensasDB) {
    mensajeCombinadoEditTipoRecompensa = "";

    if (isTipoRecompensaEditDuplicado(tiposRecompensasDB)) {
        // mensajeCombinadoEditTipoRecompensa += "El nombre de este tipo de recompensa ya ha sido registrado anteriormente. ";
        returnError = true;
        const msg = `El nombre "${nombreTipoRecompensaInputEdit.value}" ya ha sido registrado anteriormente`;
        openErrorModal("errorModalTipoRecompensa", msg);
        return false;
    }

    if (isTipoAsociadoRecompensaEdit(tiposRecompensasDB, recompensasDB)) {
        // mensajeCombinadoEditTipoRecompensa += `El tipo de recompensa con código ${codigoTipoRecompensaInputEdit.value} ya tiene recompensas asociadas, no puede editarlo.`;
        returnError = true;
        const msg = `El Tipo de Recompensa con código ${codigoTipoRecompensaInputEdit.value} tiene recompensas asociadas, no puede editar su nombre`;
        openErrorModal("errorModalTipoRecompensa", msg);
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
            generalEditTipoRecompensaError.textContent = mensajeCombinadoEditTipoRecompensa;
            generalEditTipoRecompensaError.classList.add("shown");
        }
	} else {
        generalEditTipoRecompensaError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalEditTipoRecompensaError.classList.add("shown");
    }
}
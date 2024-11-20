let codigoTipoRecompensaInputEdit = document.getElementById('codigoTipoRecompensaInputEdit');
let idNumberTipoRecompensaInputEdit = document.getElementById('idNumberTipoRecompensaEdit');
let nombreTipoRecompensaInputEdit = document.getElementById('nombreTipoRecompensaInputEdit');
let searchEditTipoRecompensaMessageError = document.getElementById('searchEditTipoRecompensaError');
let generalEditTipoRecompensaError = document.getElementById('generalEditTipoRecompensaError');

let formEditTipoRecompensaArray = [
    codigoTipoRecompensaInputEdit,
    nombreTipoRecompensaInputEdit,
];

function selectOptionEditTipoRecompensa(value, idNumberTipoRecompensa, nombreTipoRecompensa, idInput, idOptions, someHiddenIdInputsArray) {
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
        nombreTipoRecompensaInputEdit.value = sanitizednombreTipoRecompensa;
        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberTipoRecompensa;
        searchEditTipoRecompensaMessageError.classList.remove("shown");
    } else {
        nombreTipoRecompensaInputEdit.value = "";
    }
}

function validarCamposCorrectosTipoRecompensaEdit() {
    mensajeCombinadoEditOficio = "";
    var returnError = false;

    /*if (stockRecompensaInputEdit.value == 0) {
        mensajeCombinadoEditOficio += " El stock no puede ser 0.";
        returnError = true;
	}*/
    
    if (returnError) {
        return false;
    }

    generalEditTipoRecompensaError.classList.remove("shown");
    return true;
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

function guardarModalEditarTipoRecompensa(idModal, idForm) {
    if (validarCamposVaciosFormularioTipoRecompensaEdit()) {
        if (validarCamposCorrectosTipoRecompensaEdit()) {
            generalEditTipoRecompensaError.classList.remove("shown");
            guardarModal(idModal, idForm);	
        } else {
            generalEditTipoRecompensaError.textContent = mensajeCombinadoEditOficio;
            generalEditTipoRecompensaError.classList.add("shown");
        }
	} else {
        generalEditTipoRecompensaError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalEditTipoRecompensaError.classList.add("shown");
    }
}
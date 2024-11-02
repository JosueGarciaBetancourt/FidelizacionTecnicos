let codigoOficioInputEdit = document.getElementById('codigoOficioInputEdit');
let idNumberOficioInput = document.getElementById('idNumberOficio');
let descripcionOficioInputEdit = document.getElementById('descripcionOficioInputEdit');
let searchEditMessageError = document.getElementById('searchEditOficioError');
let generalEditOficioError = document.getElementById('generalEditOficioError');

let formEditOficioArray = [
    descripcionOficioInputEdit,
];

function selectOptionEditOficio(value, idNumberOficio, descripcionOficio, idInput, idOptions, someHiddenIdInputsArray) {
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
    const sanitizedDescripcionOficio = sanitizeString(descripcionOficio);

    // Colocar en el input la opción seleccionada 
    selectOption(value, idInput, idOptions); 

    // Actualizar los demás campos del formulario
    if (sanitizedDescripcionOficio) {
        descripcionOficioInputEdit.value = sanitizedDescripcionOficio;
        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberOficio;
        searchEditMessageError.classList.remove("shown");
    } else {
        descripcionOficioInputEdit.value = "";
    }
}

function validarCamposCorrectosOficioEdit() {
    mensajeCombinadoEditOficio = "";
    var returnError = false;

    /*if (stockRecompensaInputEdit.value == 0) {
        mensajeCombinadoEditOficio += " El stock no puede ser 0.";
        returnError = true;
	}*/
    
    if (returnError) {
        return false;
    }

    generalEditOficioError.classList.remove("shown");
    return true;
}

function validarCamposVaciosFormularioOficioEdit() {
  let allFilled = true;
  formEditOficioArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function guardarModalEditarOficio(idModal, idForm) {
    if (validarCamposVaciosFormularioOficioEdit()) {
        if (validarCamposCorrectosOficioEdit()) {
            generalEditOficioError.classList.remove("shown");
            guardarModal(idModal, idForm);	
        } else {
            generalEditOficioError.textContent = mensajeCombinadoEditOficio;
            generalEditOficioError.classList.add("shown");
        }
	} else {
        generalEditOficioError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalEditOficioError.classList.add("shown");
    }
}
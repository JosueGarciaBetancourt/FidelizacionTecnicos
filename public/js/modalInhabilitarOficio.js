let idNumberOficioInputDisable = document.getElementById('idDisableOficioInput');
let descripcionOficioInputDisable = document.getElementById('descripcionOficioInputDisable');
let searchMessageErrorOficioDisable = document.getElementById('searchDisableOficioError');
let generalDisableOficioError = document.getElementById('generalDisableOficioError');

let formDisableOficioArray = [
    descripcionOficioInputDisable,
];

function selectOptionInhabilitarOficio(value, idNumberOficio, descripcionOficio, idInput, idOptions, someHiddenIdInputsArray) {
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
    if (descripcionOficio && sanitizedDescripcionOficio) {
        descripcionOficioInputDisable.value = descripcionOficio;
        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberOficio;
        searchMessageErrorOficioDisable.classList.remove("shown");
    } else {
        descripcionOficioInputDisable.value = "";
    }
}

function validarCamposVaciosFormularioDisable() {
  let allFilled = true;
  formDisableOficioArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function guardarModalInhabilitarOficio(idModal, idForm) {
    if (validarCamposVaciosFormularioDisable()) {
        generalDisableOficioError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        generalDisableOficioError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalDisableOficioError.classList.add("shown");
    }
}
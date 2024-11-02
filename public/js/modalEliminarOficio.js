let idNumberOficioInputDelete = document.getElementById('idDeleteOficioInput');
let descripcionOficioInputDelete = document.getElementById('descripcionOficioInputDelete');
let searchMessageErrorOficioDelete = document.getElementById('searchDeleteOficioError');
let generalDeleteOficioError = document.getElementById('generalDeleteOficioError');

let formDeleteOficioArray = [
    descripcionOficioInputDelete,
];

function selectOptionEliminarOficio(value, idNumberOficio, descripcionOficio, idInput, idOptions, someHiddenIdInputsArray) {
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
        descripcionOficioInputDelete.value = descripcionOficio;
        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberOficio;
        searchMessageErrorOficioDelete.classList.remove("shown");
    } else {
        descripcionOficioInputDelete.value = "";
    }
}

function validarCamposVaciosFormularioDelete() {
  let allFilled = true;
  formDeleteOficioArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function guardarModalEliminarOficio(idModal, idForm) {
    if (validarCamposVaciosFormularioDelete()) {
        console.log("Enviando formulario satisfactoriamente");
        generalDeleteOficioError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        generalDeleteOficioError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalDeleteOficioError.classList.add("shown");
      }
}
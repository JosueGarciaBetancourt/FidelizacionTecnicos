let codigoOficioInputRestaurar = document.getElementById('codigoOficioInputRestaurar');
let idNumberOficioInputRestaurar = document.getElementById('idNumberOficioInputRestaurar');
let descripcionOficioInputRestaurar = document.getElementById('descripcionOficioInputRestaurar');
let searchRestaurarMessageError = document.getElementById('searchRestaurarOficioError');
let generalRestaurarOficioError = document.getElementById('generalRestaurarOficioError');

let formRestaurarInputsOficioArray = [
    descripcionOficioInputRestaurar,
];


function selectOptionRestaurarOficio(value, idNumberOficio, descripcionOficio, idInput, idOptions, someHiddenIdInputsArray) {

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
        descripcionOficioInputRestaurar.value = sanitizedDescripcionOficio;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberOficio;
        searchRestaurarOficioError.classList.remove("shown");
    } else {
        descripcionOficioInputRestaurar.value = "";
    }
}

function validarCamposVaciosFormularioRestaurar() {
  let allFilled = true;
  formRestaurarInputsOficioArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function guardarModalRestaurarOficio(idModal, idForm) {
    if (validarCamposVaciosFormularioRestaurar()) {
        console.log("Enviando formulario satisfactoriamente");
        generalRestaurarOficioError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        generalRestaurarOficioError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalRestaurarOficioError.classList.add("shown");
      }
}
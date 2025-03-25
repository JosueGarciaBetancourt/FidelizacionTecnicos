let codigoRangoInputRestaurar = document.getElementById('codigoRangoInputRestaurar');
let idNumberRangoInputRestaurar = document.getElementById('idNumberRangoInputRestaurar');
let descripcionRangoInputRestaurar = document.getElementById('descripcionRangoInputRestaurar');
let puntosMinimosRangoInputRestaurar = document.getElementById('puntosMinimosRangoInputRestaurar');
let searchRestaurarError = document.getElementById('searchRestaurarRangoError');
let generalRestaurarRangoError = document.getElementById('generalRestaurarRangoError');

let formRestaurarInputsRangoArray = [
    descripcionRangoInputRestaurar,
    puntosMinimosRangoInputRestaurar,
];


function selectOptionRestaurarRango(value, idNumberRango, descripcionRango, puntosMinimosRango, idInput, idOptions, someHiddenIdInputsArray) {
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
        descripcionRangoInputRestaurar.value = sanitizedDescripcionRango;
        puntosMinimosRangoInputRestaurar.value = puntosMinimosRango;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idNumberRango;
        searchRestaurarError.classList.remove("shown");
        generalRestaurarRangoError.classList.remove("shown");
    } else {
        descripcionRangoInputRestaurar.value = "";
        puntosMinimosRangoInputRestaurar.value = "";
    }
}

function validarCamposVaciosFormularioRestaurar() {
  let allFilled = true;
  formRestaurarInputsRangoArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function guardarModalRestaurarRango(idModal, idForm) {
    if (validarCamposVaciosFormularioRestaurar()) {
        generalRestaurarRangoError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        generalRestaurarRangoError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        generalRestaurarRangoError.classList.add("shown");
      }
}
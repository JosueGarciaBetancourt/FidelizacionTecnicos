let idRecompensaDeleteInput = document.getElementById('recompensaInputDelete');
let tipoRecompensaInputDelete = document.getElementById('tipoRecompensaInputDelete');
let descripcionRecompensaInputDelete = document.getElementById('descripcionRecompensaInputDelete');
let costoPuntosInputDelete = document.getElementById('costoPuntosInputDelete');
let stockRecompensaDelete = document.getElementById('stockRecompensaInputDelete');
let searchDeleteRecompensaError = document.getElementById('searchDeleteRecompensaError');
let eliminarRecompensaMessageError = document.getElementById('eliminarRecompensaMessageError');

let formDeleteInputsArray = [
    idRecompensaDeleteInput,
    tipoRecompensaInputDelete,
    descripcionRecompensaInputDelete,
    costoPuntosInputDelete, 
    // stockRecompensaDelete
];

function selectOptionEliminarRecompensa(value, idRecompensa, descripcionRecompensa, costoPuntos, stockRecompensa, tipoRecompensa, 
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
    const sanitizedDescripcionRecompensa = sanitizeString(descripcionRecompensa);

    // Colocar en el input la opción seleccionada 
    selectOption(value, idInput, idOptions); 

    // Actualizar los demás campos del formulario
    if (idRecompensa && sanitizedDescripcionRecompensa) {
        tipoRecompensaInputDelete.value = tipoRecompensa;
        descripcionRecompensaInputDelete.value = sanitizedDescripcionRecompensa;
        costoPuntosInputDelete.value = costoPuntos;
        stockRecompensaDelete.value = stockRecompensa;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idRecompensa;
        searchDeleteRecompensaError.classList.remove("shown");
    } else {
        tipoRecompensaInputDelete.value = "";
        descripcionRecompensaInputDelete.value = "";
        costoPuntosInputDelete.value = "";
        stockRecompensaDelete.value = "";
    }
}

function validarCamposVaciosFormularioDelete() {
  let allFilled = true;
  formDeleteInputsArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function guardarModalEliminarRecompensa(idModal, idForm) {
    if (validarCamposVaciosFormularioDelete()) {
        console.log("Enviando formulario satisfactoriamente");
        eliminarRecompensaMessageError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        eliminarRecompensaMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        eliminarRecompensaMessageError.classList.add("shown");
      }
}
let idRecompensaInput = document.getElementById('recompensaEditInput');
let tipoRecompensaInputEdit = document.getElementById('tipoRecompensaInputEdit');
let descripcionRecompensaInputEdit = document.getElementById('descripcionRecompensaInputEdit');
let costoPuntosInput = document.getElementById('costoPuntosInputEdit');
let searchRecompensaError = document.getElementById('searchEditRecompensaError');
let editarRecompensaMessageError = document.getElementById('editarRecompensaMessageError');

let formEditInputsArray = [
  idRecompensaInput,
  tipoRecompensaInputEdit,
  descripcionRecompensaInputEdit,
  costoPuntosInput, 
];

function selectOptionEditarRecompensa(value, idRecompensa, descripcionRecompensa, costoPuntos, tipoRecompensa, 
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
        tipoRecompensaInputEdit.value = tipoRecompensa;
        descripcionRecompensaInputEdit.value = sanitizedDescripcionRecompensa;
        costoPuntosInput.value = costoPuntos;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idRecompensa;
        searchEditRecompensaError.classList.remove("shown");
    } else {
        tipoRecompensaInputEdit.value = "";
        descripcionRecompensaInputEdit.value = "";
    }
}

function validarCamposVaciosFormularioEdit() {
  let allFilled = true;
  formEditInputsArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function guardarModalEditarRecompensa(idModal, idForm) {
    if (validarCamposVaciosFormularioEdit()) {
        console.log("Enviando formulario satisfactoriamente");
        editarRecompensaMessageError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        editarRecompensaMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        editarRecompensaMessageError.classList.add("shown");
      }
}
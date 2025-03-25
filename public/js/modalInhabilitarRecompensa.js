let idRecompensaDisableInput = document.getElementById('recompensaInputDisable');
let tipoRecompensaInputDisable = document.getElementById('tipoRecompensaInputDisable');
let descripcionRecompensaInputDisable = document.getElementById('descripcionRecompensaInputDisable');
let costoPuntosInputDisable = document.getElementById('costoPuntosInputDisable');
let stockRecompensaDisable = document.getElementById('stockRecompensaInputDisable');
let searchDisableRecompensaError = document.getElementById('searchDisableRecompensaError');
let inhabilitarRecompensaMessageError = document.getElementById('inhabilitarRecompensaMessageError');

let formDisableInputsArray = [
    idRecompensaDisableInput,
    tipoRecompensaInputDisable,
    descripcionRecompensaInputDisable,
    costoPuntosInputDisable, 
    // stockRecompensaDisable
];

function selectOptionInhabilitarRecompensa(value, idRecompensa, descripcionRecompensa, costoPuntos, stockRecompensa, tipoRecompensa, 
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
        tipoRecompensaInputDisable.value = tipoRecompensa;
        descripcionRecompensaInputDisable.value = sanitizedDescripcionRecompensa;
        costoPuntosInputDisable.value = costoPuntos;
        stockRecompensaDisable.value = stockRecompensa;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idRecompensa;
        searchDisableRecompensaError.classList.remove("shown");
    } else {
        tipoRecompensaInputDisable.value = "";
        descripcionRecompensaInputDisable.value = "";
        costoPuntosInputDisable.value = "";
        stockRecompensaDisable.value = "";
    }
}

function validarCamposVaciosFormularioDisable() {
  let allFilled = true;
  formDisableInputsArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function guardarModalInhabilitarRecompensa(idModal, idForm) {
    if (validarCamposVaciosFormularioDisable()) {
        console.log("Enviando formulario satisfactoriamente");
        inhabilitarRecompensaMessageError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        inhabilitarRecompensaMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        inhabilitarRecompensaMessageError.classList.add("shown");
      }
}
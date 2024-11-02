let idRecompensaRestaurarInput = document.getElementById('recompensaInputRestaurar');
let tipoRecompensaInputRestaurar = document.getElementById('tipoRecompensaInputRestaurar');
let descripcionRecompensaInputRestaurar = document.getElementById('descripcionRecompensaInputRestaurar');
let costoPuntosInputRestaurar = document.getElementById('costoPuntosInputRestaurar');
let stockRecompensaRestaurar = document.getElementById('stockRecompensaInputRestaurar');
let searchRestaurarRecompensaError = document.getElementById('searchRestaurarRecompensaError');
let restaurarRecompensaMessageError = document.getElementById('restaurarRecompensaGeneralMessageError');

let formRestaurarInputsArray = [
    idRecompensaRestaurarInput,
    tipoRecompensaInputRestaurar,
    descripcionRecompensaInputRestaurar,
    costoPuntosInputRestaurar, 
    stockRecompensaRestaurar
];

function selectOptionRestaurarRecompensa(value, idRecompensa, descripcionRecompensa, costoPuntos, stockRecompensa, tipoRecompensa, 
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
        tipoRecompensaInputRestaurar.value = tipoRecompensa;
        descripcionRecompensaInputRestaurar.value = sanitizedDescripcionRecompensa;
        costoPuntosInputRestaurar.value = costoPuntos;
        stockRecompensaRestaurar.value = stockRecompensa;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idRecompensa;
        searchRestaurarRecompensaError.classList.remove("shown");
    } else {
        tipoRecompensaInputRestaurar.value = "";
        descripcionRecompensaInputRestaurar.value = "";
    }
}

function validarCamposVaciosFormularioRestaurar() {
  let allFilled = true;
  formRestaurarInputsArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function guardarModalRestaurarRecompensa(idModal, idForm) {
    if (validarCamposVaciosFormularioRestaurar()) {
        console.log("Enviando formulario Restaurar Recompensa satisfactoriamente");
        restaurarRecompensaMessageError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        restaurarRecompensaMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        restaurarRecompensaMessageError.classList.add("shown");
    }
}
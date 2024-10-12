let tecnicoDeleteInput = document.getElementById('tecnicoDeleteInput');
let celularDeleteInput = document.getElementById('celularInputDelete');
let oficioDeleteInput = document.getElementById('oficioInputDelete');
let fechaNacimientoDeleteInput = document.getElementById('fechaNacimientoInputDelete');
let puntosActualesDeleteInput = document.getElementById('puntosActualesInputDelete');
let historicoPuntosDeleteInput = document.getElementById('historicoPuntosInputDelete');
let rangoInputDelete = document.getElementById('rangoInputDelete');

let searchDeleteTecnicoMessageError = document.getElementById('searchDeleteTecnicoMessageError');
let modalEliminarTecnicoMessageError = document.getElementById('modalEliminarTecnicoMessageError');

let formTecnicoDeleteInputsArray = [
	tecnicoDeleteInput,
	celularDeleteInput,
	oficioDeleteInput,
    fechaNacimientoDeleteInput,
    puntosActualesDeleteInput,
    historicoPuntosDeleteInput,
    rangoInputDelete,
];

let celularTecnicoDeleteTooltip = document.getElementById('idCelularTecnicoDeleteTooltip');


function selectOptionDeletearTecnico(value, idTecnico, nombreTecnico, celularTecnico, oficioTecnico, fechaNacimiento_Tecnico,
    totalPuntosActuales_Tecnico, historicoPuntos_Tecnico, rangoTecnico, idInput, idOptions, someHiddenIdInputsArray) {
    
    // Colocar en el input la opción seleccionada 
    if (idInput && idOptions) {
        selectOption(value, idInput, idOptions); 
    }
    
    // Actualizar los demás campos del formulario
    if (celularTecnico && oficioTecnico && fechaNacimiento_Tecnico && totalPuntosActuales_Tecnico && historicoPuntos_Tecnico && 
        rangoTecnico && someHiddenIdInputsArray) {
       
        celularDeleteInput.value = celularTecnico;
        oficioDeleteInput.value = oficioTecnico;
        fechaNacimientoDeleteInput.value = fechaNacimiento_Tecnico;
        puntosActualesDeleteInput.value = totalPuntosActuales_Tecnico;
        historicoPuntosDeleteInput.value = historicoPuntos_Tecnico;
        rangoInputDelete.value = rangoTecnico;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idTecnico;
        searchDeleteTecnicoMessageError.classList.remove("shown");
    } else {
        celularDeleteInput.value = "";
        oficioDeleteInput.value = "";
        fechaNacimientoDeleteInput.value = "";
        puntosActualesDeleteInput.value = "";
        historicoPuntosDeleteInput.value = "";
        rangoInputDelete.value = "";
    }
}

function validarCamposVaciosFormularioDelete() {
  let allFilled = true;
  formTecnicoDeleteInputsArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function guardarModalEliminarTecnico(idModal, idForm) {
    if (validarCamposVaciosFormularioDelete()) {
		modalEliminarTecnicoMessageError.classList.remove("shown");
		guardarModal(idModal, idForm);	
    } else {
        modalEliminarTecnicoMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        modalEliminarTecnicoMessageError.classList.add("shown");
	}
}
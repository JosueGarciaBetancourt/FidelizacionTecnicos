let tecnicoRecontratarInput = document.getElementById('tecnicoRecontratarInput');
let celularRecontratarInput = document.getElementById('celularInputRecontratar');
let oficioRecontratarInput = document.getElementById('oficioInputRecontratar');
let fechaNacimientoRecontratarInput = document.getElementById('fechaNacimientoInputRecontratar');
let puntosActualesRecontratarInput = document.getElementById('puntosActualesInputRecontratar');
let historicoPuntosRecontratarInput = document.getElementById('historicoPuntosInputRecontratar');
let rangoInputRecontratar = document.getElementById('rangoInputRecontratar');
let searchRecontratarTecnicoMessageError = document.getElementById('searchRecontratarTecnicoMessageError');
let RecontratarTecnicoMessageError = document.getElementById('RecontratarTecnicoMessageError');
let celularTecnicoRecontratarHiddenInput = document.getElementById('idcelularTecnicoRecontratarInput');

let formTecnicoRecontratarInputsArray = [
	tecnicoRecontratarInput,
	celularRecontratarInput,
	oficioRecontratarInput,
    fechaNacimientoRecontratarInput,
    puntosActualesRecontratarInput,
    historicoPuntosRecontratarInput,
    rangoInputRecontratar,
];

let celularTecnicoRecontratarTooltip = document.getElementById('idCelularTecnicoRecontratarTooltip');

function selectOptionRecontratarTecnico(value, idTecnico, nombreTecnico, celularTecnico, oficioTecnico, fechaNacimiento_Tecnico,
    totalPuntosActuales_Tecnico, historicoPuntos_Tecnico, rangoTecnico, idInput, idOptions, someHiddenIdInputsArray) {
    
    // Colocar en el input la opción seleccionada 
    if (idInput && idOptions) {
        selectOption(value, idInput, idOptions); 
    }
    
    // Actualizar los demás campos del formulario
    if (celularTecnico && oficioTecnico && fechaNacimiento_Tecnico && totalPuntosActuales_Tecnico && historicoPuntos_Tecnico && 
        rangoTecnico && someHiddenIdInputsArray) {
       
        celularRecontratarInput.value = celularTecnico;
        oficioRecontratarInput.value = oficioTecnico;
        fechaNacimientoRecontratarInput.value = fechaNacimiento_Tecnico;
        puntosActualesRecontratarInput.value = totalPuntosActuales_Tecnico;
        historicoPuntosRecontratarInput.value = historicoPuntos_Tecnico;
        rangoInputRecontratar.value = rangoTecnico;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idTecnico;
        searchRecontratarTecnicoMessageError.classList.remove("shown");
    } else {
        celularRecontratarInput.value = "";
        oficioRecontratarInput.value = "";
        fechaNacimientoRecontratarInput.value = "";
        puntosActualesRecontratarInput.value = "";
        historicoPuntosRecontratarInput.value = "";
        rangoInputRecontratar.value = "";
    }
}

function validarCamposVaciosFormularioRecontratar() {
  let allFilled = true;
  formTecnicoRecontratarInputsArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function validarCamposCorrectosFormularioTecnicoRecontratar() {
    if (celularRecontratarInput.value.length != 9) {
        showHideTooltip(celularTecnicoRecontratarTooltip, "El número de celular debe contener 9 dígitos");
        RecontratarTecnicoMessageError.textContent = "El número de celular debe contener 9 dígitos";
        RecontratarTecnicoMessageError.classList.add("shown");
        return false
    }
    
    return true;
}

function guardarModalRecontratarTecnico(idModal, idForm) {
    if (validarCamposVaciosFormularioRecontratar()) {
        if (validarCamposCorrectosFormularioTecnicoRecontratar()) {
            RecontratarTecnicoMessageError.classList.remove("shown");
            guardarModal(idModal, idForm);	
        } 
    } else {
        RecontratarTecnicoMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        RecontratarTecnicoMessageError.classList.add("shown");
      }
}
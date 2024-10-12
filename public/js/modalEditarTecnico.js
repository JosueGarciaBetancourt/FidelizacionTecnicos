let tecnicoEditInput = document.getElementById('tecnicoEditInput');
let celularEditInput = document.getElementById('celularInputEdit');
let oficioEditInput = document.getElementById('oficioInputEdit');
let fechaNacimientoEditInput = document.getElementById('fechaNacimientoInputEdit');
let puntosActualesEditInput = document.getElementById('puntosActualesInputEdit');
let historicoPuntosEditInput = document.getElementById('historicoPuntosInputEdit');
let rangoInputEdit = document.getElementById('rangoInputEdit');
let searchEditTecnicoMessageError = document.getElementById('searchEditTecnicoMessageError');
let editarTecnicoMessageError = document.getElementById('editarTecnicoMessageError');
let celularTecnicoEditHiddenInput = document.getElementById('idcelularTecnicoEditInput');

let formTecnicoEditInputsArray = [
	tecnicoEditInput,
	celularEditInput,
	oficioEditInput,
    fechaNacimientoEditInput,
    puntosActualesEditInput,
    historicoPuntosEditInput,
    rangoInputEdit,
];

let celularTecnicoEditTooltip = document.getElementById('idCelularTecnicoEditTooltip');


function selectOptionEditarTecnico(value, idTecnico, nombreTecnico, celularTecnico, oficioTecnico, fechaNacimiento_Tecnico,
    totalPuntosActuales_Tecnico, historicoPuntos_Tecnico, rangoTecnico, idInput, idOptions, someHiddenIdInputsArray) {
    
    // Colocar en el input la opción seleccionada 
    if (idInput && idOptions) {
        selectOption(value, idInput, idOptions); 
    }
    
    // Actualizar los demás campos del formulario
    if (celularTecnico && oficioTecnico && fechaNacimiento_Tecnico && totalPuntosActuales_Tecnico && historicoPuntos_Tecnico && 
        rangoTecnico && someHiddenIdInputsArray) {
       
        celularEditInput.value = celularTecnico;
        oficioEditInput.value = oficioTecnico;
        fechaNacimientoEditInput.value = fechaNacimiento_Tecnico;
        puntosActualesEditInput.value = totalPuntosActuales_Tecnico;
        historicoPuntosEditInput.value = historicoPuntos_Tecnico;
        rangoInputEdit.value = rangoTecnico;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idTecnico;
        searchEditTecnicoMessageError.classList.remove("shown");
    } else {
        celularEditInput.value = "";
        oficioEditInput.value = "";
        fechaNacimientoEditInput.value = "";
        puntosActualesEditInput.value = "";
        historicoPuntosEditInput.value = "";
        rangoInputEdit.value = "";
    }
}

function validarCamposVaciosFormularioEdit() {
  let allFilled = true;
  formTecnicoEditInputsArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function validarCamposCorrectosFormularioTecnicoEdit() {
    if (celularEditInput.value.length != 9) {
        showHideTooltip(celularTecnicoEditTooltip, "El número de celular debe contener 9 dígitos");
        editarTecnicoMessageError.textContent = "El número de celular debe contener 9 dígitos";
        editarTecnicoMessageError.classList.add("shown");
        return false
    }
    
    return true;
}

function guardarModalEditarTecnico(idModal, idForm) {
    if (validarCamposVaciosFormularioEdit()) {
        if (validarCamposCorrectosFormularioTecnicoEdit()) {
            editarTecnicoMessageError.classList.remove("shown");
            guardarModal(idModal, idForm);	
        } 
    } else {
        editarTecnicoMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        editarTecnicoMessageError.classList.add("shown");
      }
}
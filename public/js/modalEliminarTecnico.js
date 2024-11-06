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

function selectOptionDeleteTecnico(value, idTecnico, nombreTecnico, celularTecnico, oficiosTecnico, fechaNacimiento_Tecnico,
    totalPuntosActuales_Tecnico, historicoPuntos_Tecnico, rangoTecnico, idInput, idOptions, someHiddenIdInputsArray) {
    
    // Colocar en el input la opción seleccionada 
    if (idInput && idOptions) {
        selectOption(value, idInput, idOptions); 
    }
    
    // Actualizar los demás campos del formulario
    if (celularTecnico && oficiosTecnico && fechaNacimiento_Tecnico && totalPuntosActuales_Tecnico && historicoPuntos_Tecnico && 
        rangoTecnico && someHiddenIdInputsArray) {
       
        celularDeleteInput.value = celularTecnico;
        oficioDeleteInput.value = oficiosTecnico;
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

function validateValueOnRealTimeTecnicoDelete(input, idOptions, idMessageError, someHiddenIdInputsArray, otherInputsArray = null, tecnicosDB = null) {
    const value = input.value;
    const messageError = document.getElementById(idMessageError);

    const clearHiddenInputs = () => {
        someHiddenIdInputsArray.forEach(idInput => {
            const inputElement = document.getElementById(idInput);
            if (inputElement) {
                inputElement.value = ""; // Asignar valor vacío
            }
        });
    };

    // Obtener todos los valores del item
    const allItems = getAllLiText(idOptions);

    // Comparar el valor ingresado con la lista de items
    const isItemFound = allItems.includes(value);

    // Dividir el valor en partes (id y nombre)
    const [id, nombre] = value.split(' | ');

    const clearInputs = () => {
    clearHiddenInputs();
        if (otherInputsArray) {
            otherInputsArray.forEach(idOtherInput => {
            const otherInputElement = document.getElementById(idOtherInput);
                if (otherInputElement) {
                    otherInputElement.value = ""; 
                }
            });
        }
    };

    if (value === "") {
        messageError.classList.remove('shown');
        clearInputs();
    } else if (!isItemFound) {
        clearInputs();
        messageError.classList.add('shown'); 
    } else {
        // Se encontró el item buscado
        messageError.classList.remove('shown');

        if (tecnicosDB) {
            const objTecnico = returnObjTecnicoById(id, tecnicosDB);

            // Actualizar los inputs ocultos
            if (id) {
                document.getElementById(someHiddenIdInputsArray[0]).value = objTecnico['idTecnico'];
            }

            // Rellenar otros inputs visibles si se requiere
            if (otherInputsArray) {
                document.getElementById(otherInputsArray[0]).value = objTecnico['celularTecnico'];
                document.getElementById(otherInputsArray[1]).value = objTecnico['idNameOficioTecnico'];
                document.getElementById(otherInputsArray[2]).value = objTecnico['fechaNacimiento_Tecnico'];
                document.getElementById(otherInputsArray[3]).value = objTecnico['totalPuntosActuales_Tecnico'];
                document.getElementById(otherInputsArray[4]).value = objTecnico['historicoPuntos_Tecnico'];
                document.getElementById(otherInputsArray[5]).value = objTecnico['rangoTecnico'];
            }
        }
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
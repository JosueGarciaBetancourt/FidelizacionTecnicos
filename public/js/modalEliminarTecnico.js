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

function selectOptionEditOficio(value, idInput, idOptions) {
    // Extrae el ID del oficio actual seleccionado
    const oficioId = parseInt(value.split('-')[0]);

    // Referencias a los elementos de DOM necesarios
    const input = document.getElementById(idInput);
    const options = document.getElementById(idOptions);

    if (!input || !options) {
        console.error(`No se encontraron los elementos con IDs ${idInput} o ${idOptions} en el DOM`);
        return;
    }

    let oficiosActuales = [];

    // Verifica si oficioDeleteInput está vacío
    if (oficioDeleteInput.value.trim() === "") {
        // Primer oficio agregado
        input.value = value;
        idsOficioEditArrayInput.value = JSON.stringify([oficioId]);
    } else {
        // Obtiene los oficios actuales y los separa
        oficiosActuales = oficioDeleteInput.value.split(' | ')
            .map(oficio => oficio.trim())
            .filter(oficio => oficio !== '');

        // Agrega el nuevo oficio si no existe
        if (!oficiosActuales.some(oficio => parseInt(oficio.split('-')[0]) === oficioId)) {
            oficiosActuales.push(value);
        }

        // Ordena los oficios por ID
        oficiosActuales.sort((a, b) => {
            const idA = parseInt(a.split('-')[0]);
            const idB = parseInt(b.split('-')[0]);
            return idA - idB;
        });

        // Actualiza el input con los oficios ordenados
        input.value = oficiosActuales.join(' | ');

        // Actualiza el input oculto con los IDs ordenados
        const idsOrdenados = oficiosActuales.map(oficio => parseInt(oficio.split('-')[0]));
        idsOficioEditArrayInput.value = JSON.stringify(idsOrdenados);
    }

    options.classList.remove('show');
    
    // Para debug
    console.log('IDs de oficios seleccionados:', JSON.parse(idsOficioEditArrayInput.value));
}

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
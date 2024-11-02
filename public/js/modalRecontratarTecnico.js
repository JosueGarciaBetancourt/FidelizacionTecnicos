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
let idsOficioRecontratarArrayInput = document.getElementById('idsOficioRecontratarArrayInput');

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

function selectOptionRecontratarOficio(value, idInput, idOptions) {
    // Extrae el ID del oficio actual seleccionado
    const oficioId = parseInt(value.split('-')[0]);

    // Referencias a los elementos de DOM necesarios
    const input = document.getElementById(idInput);
    const options = document.getElementById(idOptions);

    if (!input || !options) {
        console.error(`No se encontraron los elementos con IDs ${idInput} o ${idOptions} en el DOM`);
        return;
    }

    let oficiosRecontratarActuales = [];

    // Verifica si oficioRecontratarInput está vacío
    if (oficioRecontratarInput.value.trim() === "") {
        // Primer oficio agregado
        input.value = value;
        idsOficioRecontratarArrayInput.value = JSON.stringify([oficioId]);
    } else {
        // Obtiene los oficios actuales y los separa
        oficiosRecontratarActuales = oficioRecontratarInput.value.split(' | ')
            .map(oficio => oficio.trim())
            .filter(oficio => oficio !== '');

        // Agrega el nuevo oficio si no existe
        if (!oficiosRecontratarActuales.some(oficio => parseInt(oficio.split('-')[0]) === oficioId)) {
            oficiosRecontratarActuales.push(value);
        }

        // Ordena los oficios por ID
        oficiosRecontratarActuales.sort((a, b) => {
            const idA = parseInt(a.split('-')[0]);
            const idB = parseInt(b.split('-')[0]);
            return idA - idB;
        });

        // Actualiza el input oculto con los IDs ordenados
        const idsOrdenados = oficiosRecontratarActuales.map(oficio => parseInt(oficio.split('-')[0]));
        idsOficioRecontratarArrayInput.value = JSON.stringify(idsOrdenados);

        // Actualiza el input con los oficios ordenados
        input.value = oficiosRecontratarActuales.join(' | ');
    }

    options.classList.remove('show');
    
    // Para debug
    console.log('IDs de oficios seleccionados:', JSON.parse(idsOficioRecontratarArrayInput.value));
}

function cleanHiddenOficiosRecontratarInput() {
    idsOficioRecontratarArrayInput.value = "";
}

function fillHiddenOficiosRecontratarInput (oficiosTecnico) {
    if (!idsOficioRecontratarArrayInput) {
        console.error("No se encontró el input oculto para los IDs de oficios.");
        return;
    }

    // Verifica si oficiosTecnico es una cadena
    if (typeof oficiosTecnico === 'string') {
        // Separa los oficios por ' | ' y extrae los IDs
        const oficiosArray = oficiosTecnico.split(' | ').map(oficio => oficio.trim());
        const idsOficios = oficiosArray.map(oficio => parseInt(oficio.split('-')[0]));

        // Almacena los IDs en el input oculto como JSON
        idsOficioRecontratarArrayInput.value = JSON.stringify(idsOficios);
    } else if (Array.isArray(oficiosTecnico)) {
        // Si ya es un array, simplemente extrae los IDs
        const idsOficios = oficiosTecnico.map(oficio => parseInt(oficio.split('-')[0]));

        // Almacena los IDs en el input oculto como JSON
        idsOficioRecontratarArrayInput.value = JSON.stringify(idsOficios);
    } else {
        console.error("El parámetro oficiosTecnico debe ser un string o un array.");
    }

    // Para debug
    console.log('IDs de oficios llenados en el input oculto:', JSON.parse(idsOficioRecontratarArrayInput.value));
}

function selectOptionRecontratarTecnico(value, idTecnico, nombreTecnico, celularTecnico, oficiosTecnico, fechaNacimiento_Tecnico,
    totalPuntosActuales_Tecnico, historicoPuntos_Tecnico, rangoTecnico, idInput, idOptions, someHiddenIdInputsArray) {
    
    // Colocar en el input la opción seleccionada 
    if (idInput && idOptions) {
        selectOption(value, idInput, idOptions); 
    }
    
    console.log(celularTecnico, oficiosTecnico);

    // Actualizar los demás campos del formulario
    if (celularTecnico && oficiosTecnico && fechaNacimiento_Tecnico && totalPuntosActuales_Tecnico && historicoPuntos_Tecnico && 
        rangoTecnico && someHiddenIdInputsArray) {
       
        celularRecontratarInput.value = celularTecnico;
        oficioRecontratarInput.value = oficiosTecnico;
        fechaNacimientoRecontratarInput.value = fechaNacimiento_Tecnico;
        puntosActualesRecontratarInput.value = totalPuntosActuales_Tecnico;
        historicoPuntosRecontratarInput.value = historicoPuntos_Tecnico;
        rangoInputRecontratar.value = rangoTecnico;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idTecnico;
        searchRecontratarTecnicoMessageError.classList.remove("shown");
        fillHiddenOficiosRecontratarInput(oficiosTecnico);
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
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

function validateValueOnRealTimeTecnicoRecontratar(input, idOptions, idMessageError, someHiddenIdInputsArray, otherInputsArray = null, tecnicosBorradosDB = null) {
    const value = input.value;
    const messageError = document.getElementById(idMessageError);

    console.log(tecnicosBorradosDB);

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

        if (tecnicosBorradosDB) {
            const objTecnico = returnObjTecnicoById(id, tecnicosBorradosDB);
            // Actualizar los inputs ocultos
            if (id) {
                document.getElementById(someHiddenIdInputsArray[0]).value = objTecnico['idTecnico'];
                document.getElementById(someHiddenIdInputsArray[1]).value = objTecnico['idsOficioTecnico'];
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
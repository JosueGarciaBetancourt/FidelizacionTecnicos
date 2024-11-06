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
let idsOficioEditArrayInput = document.getElementById('idsOficioEditArrayInput');

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

    // Verifica si oficioEditInput está vacío
    if (oficioEditInput.value.trim() === "") {
        // Primer oficio agregado
        input.value = value;
        idsOficioEditArrayInput.value = JSON.stringify([oficioId]);
    } else {
        // Obtiene los oficios actuales y los separa
        oficiosActuales = oficioEditInput.value.split(' | ')
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

        // Actualiza el input oculto con los IDs ordenados
        const idsOrdenados = oficiosActuales.map(oficio => parseInt(oficio.split('-')[0]));
        idsOficioEditArrayInput.value = JSON.stringify(idsOrdenados);

        // Actualiza el input con los oficios ordenados
        input.value = oficiosActuales.join(' | ');
    }

    options.classList.remove('show');
    
    // Para debug
    //console.log('IDs de oficios seleccionados:', JSON.parse(idsOficioEditArrayInput.value));
}

function cleanHiddenOficiosEditInput() {
    idsOficioEditArrayInput.value = "";
}

function fillHiddenOficiosEditInput (oficiosTecnico) {
    // Suponiendo que tienes un input oculto para los IDs de los oficios
    const idsOficioEditArrayInput = document.getElementById('idsOficioEditArrayInput');

    if (!idsOficioEditArrayInput) {
        console.error("No se encontró el input oculto para los IDs de oficios.");
        return;
    }

    // Verifica si oficiosTecnico es una cadena
    if (typeof oficiosTecnico === 'string') {
        // Separa los oficios por ' | ' y extrae los IDs
        const oficiosArray = oficiosTecnico.split(' | ').map(oficio => oficio.trim());
        const idsOficios = oficiosArray.map(oficio => parseInt(oficio.split('-')[0]));

        // Almacena los IDs en el input oculto como JSON
        idsOficioEditArrayInput.value = JSON.stringify(idsOficios);
    } else if (Array.isArray(oficiosTecnico)) {
        // Si ya es un array, simplemente extrae los IDs
        const idsOficios = oficiosTecnico.map(oficio => parseInt(oficio.split('-')[0]));

        // Almacena los IDs en el input oculto como JSON
        idsOficioEditArrayInput.value = JSON.stringify(idsOficios);
    } else {
        console.error("El parámetro oficiosTecnico debe ser un string o un array.");
    }

    // Para debug
    //console.log('IDs de oficios llenados en el input oculto:', JSON.parse(idsOficioEditArrayInput.value));
}

function selectOptionEditarTecnico(value, idTecnico, nombreTecnico, celularTecnico, oficiosTecnico, fechaNacimiento_Tecnico,
    totalPuntosActuales_Tecnico, historicoPuntos_Tecnico, rangoTecnico, idInput, idOptions, someHiddenIdInputsArray) {

    // Colocar en el input la opción seleccionada 
    if (idInput && idOptions) {
        selectOption(value, idInput, idOptions); 
    }
    
    // Actualizar los demás campos del formulario
    if (celularTecnico && oficiosTecnico && fechaNacimiento_Tecnico && totalPuntosActuales_Tecnico && historicoPuntos_Tecnico && 
        rangoTecnico && someHiddenIdInputsArray) {
       
        celularEditInput.value = celularTecnico;
        oficioEditInput.value = oficiosTecnico;
        fechaNacimientoEditInput.value = fechaNacimiento_Tecnico;
        puntosActualesEditInput.value = totalPuntosActuales_Tecnico;
        historicoPuntosEditInput.value = historicoPuntos_Tecnico;
        rangoInputEdit.value = rangoTecnico;

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idTecnico;
        searchEditTecnicoMessageError.classList.remove("shown");
        fillHiddenOficiosEditInput(oficiosTecnico);
    } else {
        celularEditInput.value = "";
        oficioEditInput.value = "";
        fechaNacimientoEditInput.value = "";
        puntosActualesEditInput.value = "";
        historicoPuntosEditInput.value = "";
        rangoInputEdit.value = "";
    }
}

function validateValueOnRealTimeTecnicoEdit(input, idOptions, idMessageError, someHiddenIdInputsArray, otherInputsArray = null, tecnicosDB = null) {
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
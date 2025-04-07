let tecnicoRecontratarInput = document.getElementById('tecnicoRecontratarInput');
let celularRecontratarInput = document.getElementById('celularInputRecontratar');
let fechaNacimientoRecontratarInput = document.getElementById('fechaNacimientoInputRecontratar');
let puntosActualesRecontratarInput = document.getElementById('puntosActualesInputRecontratar');
let historicoPuntosRecontratarInput = document.getElementById('historicoPuntosInputRecontratar');
let rangoInputRecontratar = document.getElementById('rangoInputRecontratar');
let searchRecontratarTecnicoMessageError = document.getElementById('searchRecontratarTecnicoMessageError');
let recontratarTecnicoMessageError = document.getElementById('recontratarTecnicoMessageError');
let celularTecnicoRecontratarHiddenInput = document.getElementById('idcelularTecnicoRecontratarInput');
let idsOficioRecontratarArrayInput = document.getElementById('idsOficioRecontratarArrayInput');

let formTecnicoRecontratarInputsArray = [
	tecnicoRecontratarInput,
	celularRecontratarInput,
    fechaNacimientoRecontratarInput,
    puntosActualesRecontratarInput,
    historicoPuntosRecontratarInput,
    rangoInputRecontratar,
    idsOficioRecontratarArrayInput
];

let celularTecnicoRecontratarTooltip = document.getElementById('idCelularTecnicoRecontratarTooltip');

document.addEventListener("DOMContentLoaded", function() {
    let oficiosSeleccionadosIdsDelete = [];

    if (typeof window.registerExternFunction === "function") {
        window.registerExternFunction("idMultiSelectDropdownContainer_RecontratarTecnico", "selectOptionOficio", function (optionValue) {
            var oficioId;

            if (!isNaN(optionValue) && Number.isInteger(Number(optionValue))) {
                oficioId = optionValue
            } else {
                oficioId = parseInt(optionValue.trim().split('-')[0]);
            }

            oficiosSeleccionadosIdsDelete = [...new Set([...oficiosSeleccionadosIdsDelete, oficioId])].sort((a, b) => a - b);
            idsOficioRecontratarArrayInput.value = JSON.stringify(oficiosSeleccionadosIdsDelete);
        });
    
        window.registerExternFunction("idMultiSelectDropdownContainer_RecontratarTecnico", "deleteOptionOficio", function (optionValue) {
            var oficioId;

            if (!isNaN(optionValue) && Number.isInteger(Number(optionValue))) {
                oficioId = optionValue
            } else {
                oficioId = parseInt(optionValue.trim().split('-')[0]);
            }
    
            oficiosSeleccionadosIdsDelete = oficiosSeleccionadosIdsDelete.filter(id => id !== oficioId); // Remover el id
    
            if (typeof idsOficioRecontratarArrayInput !== "undefined" && idsOficioRecontratarArrayInput !== null) {
                idsOficioRecontratarArrayInput.value = oficiosSeleccionadosIdsDelete.length === 0 ? "" : JSON.stringify(oficiosSeleccionadosIdsDelete);
            }
        });
    } else {
        console.error("window.registerExternFunction no está definido.");
    }
});

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
}

function clearMultiSelectDropdownRecontratarTecnico() {
    const clearTagsMultiSelectDropdown = window.getExternFunction("idMultiSelectDropdownContainer_RecontratarTecnico", "clearTagsMultiSelectDropdown");
    if (clearTagsMultiSelectDropdown) {
        clearTagsMultiSelectDropdown();
    }
}

function fillMultiSelectDropdownRecontratarTecnico(idNameOficioTecnico) {
    const idsNamesOficios = idNameOficioTecnico.split('|').map(item => item.trim()).join(', ');
    idsOficioRecontratarArrayInput.value = idsNamesOficios;                                     

    const fillTagsMultiSelectDropdown = window.getExternFunction("idMultiSelectDropdownContainer_RecontratarTecnico", "fillTagsMultiSelectDropdown");
    if (fillTagsMultiSelectDropdown) {
        fillTagsMultiSelectDropdown(idsNamesOficios.split(',').map(item => item.trim()));
    }
}

function selectOptionRecontratarTecnico(value, idTecnico, nombreTecnico, celularTecnico, oficiosTecnico, fechaNacimiento_Tecnico,
    totalPuntosActuales_Tecnico, historicoPuntos_Tecnico, nombre_Rango, idInput, idOptions, someHiddenIdInputsArray) {
    
    // Colocar en el input la opción seleccionada 
    if (idInput && idOptions) {
        selectOption(value, idInput, idOptions); 
    }
    
    // Actualizar los demás campos del formulario
    if (celularTecnico && oficiosTecnico && fechaNacimiento_Tecnico && totalPuntosActuales_Tecnico && historicoPuntos_Tecnico && 
        nombre_Rango && someHiddenIdInputsArray) {
       
        celularRecontratarInput.value = celularTecnico;
        fechaNacimientoRecontratarInput.value = fechaNacimiento_Tecnico;
        puntosActualesRecontratarInput.value = totalPuntosActuales_Tecnico;
        historicoPuntosRecontratarInput.value = historicoPuntos_Tecnico;
        rangoInputRecontratar.value = nombre_Rango;
        fillMultiSelectDropdownRecontratarTecnico(oficiosTecnico);

        // Llenar campos ocultos
        document.getElementById(someHiddenIdInputsArray[0]).value = idTecnico;
        searchRecontratarTecnicoMessageError.classList.remove("shown");
        fillHiddenOficiosRecontratarInput(oficiosTecnico);
    } else {
        celularRecontratarInput.value = "";
        fechaNacimientoRecontratarInput.value = "";
        puntosActualesRecontratarInput.value = "";
        historicoPuntosRecontratarInput.value = "";
        rangoInputRecontratar.value = "";
        idsOficioRecontratarArrayInput.value = "";
    }
}

function validateValueOnRealTimeTecnicoRecontratar(input, idOptions, idMessageError, someHiddenIdInputsArray, otherInputsArray = null, tecnicosBorradosDB = null) {
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
        clearMultiSelectDropdownRecontratarTecnico();
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
                fillMultiSelectDropdownRecontratarTecnico(objTecnico['idNameOficioTecnico']);
                document.getElementById(otherInputsArray[2]).value = objTecnico['fechaNacimiento_Tecnico'];
                document.getElementById(otherInputsArray[3]).value = objTecnico['totalPuntosActuales_Tecnico'];
                document.getElementById(otherInputsArray[4]).value = objTecnico['historicoPuntos_Tecnico'];
                document.getElementById(otherInputsArray[5]).value = objTecnico['nombre_Rango'];
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
        recontratarTecnicoMessageError.textContent = "El número de celular debe contener 9 dígitos";
        recontratarTecnicoMessageError.classList.add("shown");
        return false
    }
    
    return true;
}

async function guardarModalRecontratarTecnico(idModal, idForm) {
    if (validarCamposVaciosFormularioRecontratar()) {
        if (validarCamposCorrectosFormularioTecnicoRecontratar()) {
            const idTecnico = tecnicoRecontratarInput.value.trim().split('|')[0];
            const celularTecnico = celularRecontratarInput.value;

            // Validar celular duplicado con fetch
            const url = `${baseUrlMAIN}/verificar-celularTecnico`;
        
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfTokenMAIN
                },
                body: JSON.stringify({ idTecnico: idTecnico,  celularTecnico: celularTecnico})
            });
        
            if (!response.ok) {
                throw new Error('Error en la comunicación con el servidor.');
            }
        
            const data = await response.json();
        
            if (data.exists) {
                recontratarTecnicoMessageError.textContent = data.message;
                recontratarTecnicoMessageError.classList.add('shown');
                return; 
            }

            recontratarTecnicoMessageError.classList.remove("shown");
            guardarModal(idModal, idForm);	
        } 
    } else {
        recontratarTecnicoMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        recontratarTecnicoMessageError.classList.add("shown");
      }
}
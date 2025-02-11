let tecnicoDeleteInput = document.getElementById('tecnicoDeleteInput');
let tecnicoDeleteOptions = document.getElementById('tecnicoDeleteOptions');
let celularDeleteInput = document.getElementById('celularInputDelete');
let fechaNacimientoDeleteInput = document.getElementById('fechaNacimientoInputDelete');
let puntosActualesDeleteInput = document.getElementById('puntosActualesInputDelete');
let historicoPuntosDeleteInput = document.getElementById('historicoPuntosInputDelete');
let rangoInputDelete = document.getElementById('rangoInputDelete');
let searchDeleteTecnicoMessageError = document.getElementById('searchDeleteTecnicoMessageError');
let eliminarTecnicoMessageError = document.getElementById('eliminarTecnicoMessageError');
let someHiddenIdInputsTecnicoDeleteArray = ['idDeleteTecnicoInput'];

let formTecnicoDeleteInputsArray = [
	tecnicoDeleteInput,
	celularDeleteInput,
    fechaNacimientoDeleteInput,
    puntosActualesDeleteInput,
    historicoPuntosDeleteInput,
    rangoInputDelete,
];

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
		eliminarTecnicoMessageError.classList.remove("shown");
		guardarModal(idModal, idForm);	
    } else {
        eliminarTecnicoMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        eliminarTecnicoMessageError.classList.add("shown");
	}
}

function clearMultiSelectDropdownEliminarTecnico() {
    const clearTagsMultiSelectDropdown = window.getExternFunction("idMultiSelectDropdownContainer_EliminarTecnico", "clearTagsMultiSelectDropdown");
    if (clearTagsMultiSelectDropdown) {
        clearTagsMultiSelectDropdown();
    }
}

function fillMultiSelectDropdownEliminarTecnico(idNameOficioTecnico) {
    const idsNamesOficios = idNameOficioTecnico.split('|').map(item => item.trim()).join(', ');
    const fillTagsMultiSelectDropdown = window.getExternFunction("idMultiSelectDropdownContainer_EliminarTecnico", "fillTagsMultiSelectDropdown");
    
    if (fillTagsMultiSelectDropdown) {
        fillTagsMultiSelectDropdown(idsNamesOficios.split(',').map(item => item.trim()));
    }
}

/* INICIO Funciones para manejar el input dinámico */
let currentPageTecnicoDelete = 1;

function selectOptionTecnicoDelete(value, tecnico) {
    const hiddenIdTecnicoInput = document.getElementById(someHiddenIdInputsTecnicoDeleteArray[0]);

    // Colocar en el input la opción seleccionada 
    selectOption(value, tecnicoDeleteInput.id, tecnicoDeleteOptions.id); 
    
    //consoleLogJSONItems(tecnico);
    
    if (!tecnico) {
        celularDeleteInput.value = "";
        clearMultiSelectDropdownEliminarTecnico();
        fechaNacimientoDeleteInput.value = "";
        puntosActualesDeleteInput.value = "";
        historicoPuntosDeleteInput.value = "";
        rangoInputDelete.value = "";
        hiddenIdTecnicoInput.value  = "";
        return;
    }
   
    // Llenar los demás campos del formulario
    celularDeleteInput.value = tecnico.celularTecnico;
    fillMultiSelectDropdownEliminarTecnico(tecnico.idNameOficioTecnico);
    fechaNacimientoDeleteInput.value = tecnico.fechaNacimiento_Tecnico;
    puntosActualesDeleteInput.value = tecnico.totalPuntosActuales_Tecnico;
    historicoPuntosDeleteInput.value = tecnico.historicoPuntos_Tecnico;
    rangoInputDelete.value = tecnico.rangoTecnico;

    // Llenar campos ocultos
    hiddenIdTecnicoInput.value = tecnico.idTecnico;
    searchDeleteTecnicoMessageError.classList.remove("shown");
}

async function filterOptionsTecnicoDelete(input, idOptions) {
    const filter = input.value.trim().toUpperCase();
    const ul = document.getElementById(idOptions);
    const url = `${baseUrlMAIN}/dashboard-tecnicos/getFilteredTecnicos`;

    const response = await fetch(url, {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfTokenMAIN,
        },
        body: JSON.stringify({ filter: filter }),
    });

    // Limpiar las opciones anteriores
    ul.innerHTML = "";

    // Verificar el estado de la respuesta
    if (!response.ok) {
        const errorData = await response.json();
        if (errorData.data == "") {
            console.warn(`Error en filterOptionsTecnicoDelete: ${errorData.message}`);
            const li = document.createElement('li');
            li.textContent = 'No se encontraron técnicos';
            ul.appendChild(li);
            ul.classList.add('show');
        }
        return;
    }

    const data = await response.json();

    // Mostrar las opciones y agregarlas dinámicamente
    data.data.forEach(tecnico => {
        const li = document.createElement('li');
        const value = `${tecnico.idTecnico} | ${tecnico.nombreTecnico}`;
        const tecnicoData = JSON.stringify(tecnico);
        
        li.textContent = value;
        li.setAttribute('onclick', `selectOptionTecnicoDelete('${value}', ${tecnicoData})`);
        ul.appendChild(li);
    });

    ul.classList.add('show');
}

async function validateValueOnRealTimeTecnicoDelete(input, idMessageError, otherInputsArray = null) {
    const idNombreTecnico = input.value.trim();
    const messageError = document.getElementById(idMessageError);
    const url = `${baseUrlMAIN}/dashboard-tecnicos/getTecnicoByIdNombre`;

    const clearInputs = () => {
        if (Array.isArray(otherInputsArray)) {
            otherInputsArray.forEach(idOtherInput => {
                const otherInputElement = document.getElementById(idOtherInput);
                if (otherInputElement) {
                    otherInputElement.value = "";
                }
            });
        }
        clearMultiSelectDropdownEliminarTecnico();
    };

    if (idNombreTecnico === "") {
        messageError.classList.remove('shown');
        clearInputs();
        return;
    }

    // Validar que el formato sea válido
    const regex = /^\d+\s\|\s.+$/;

    if (!regex.test(idNombreTecnico)) {
        messageError.classList.add('shown');
        clearInputs();
        return;
    }

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfTokenMAIN,
            },
            body: JSON.stringify({ idNombreTecnico: idNombreTecnico }),
        });

        if (!response.ok) {
            messageError.classList.add('shown');
            clearInputs();
            return;
        }

        const data = await response.json();

        if (!data.tecnicoBuscado) {
            clearInputs();
            messageError.classList.add('shown');
            return;
        }

        messageError.classList.remove('shown');

        // Obtener el objeto tecnicoBuscado de la respuesta JSON
        const tecnicoBuscado = data.tecnicoBuscado;

        // Rellenar otros inputs visibles si se requiere
        if (otherInputsArray) {
            document.getElementById(otherInputsArray[0]).value = tecnicoBuscado.celularTecnico;
            fillMultiSelectDropdownEliminarTecnico(tecnicoBuscado.idNameOficioTecnico);
            document.getElementById(otherInputsArray[2]).value = tecnicoBuscado.fechaNacimiento_Tecnico;
            document.getElementById(otherInputsArray[3]).value = tecnicoBuscado.totalPuntosActuales_Tecnico;
            document.getElementById(otherInputsArray[4]).value = tecnicoBuscado.historicoPuntos_Tecnico;
            document.getElementById(otherInputsArray[5]).value = tecnicoBuscado.rangoTecnico;
        }
    } catch (error) {
        console.error(`Error inesperado en validateValueOnRealTimeTecnicoEdit: ${error.message}`);
        clearInputs();
    }
}

// Manejador del evento de scroll
async function loadMoreOptionsTecnicoDelete(event) {
    const optionsListUL = event.target;
    const threshold = 0.6; // 60% del contenido visible

    // Si el usuario ha llegado al final de la lista (se detecta el scroll)
    if (optionsListUL.scrollTop + optionsListUL.clientHeight >= optionsListUL.scrollHeight * threshold) {
        await loadPaginatedOptionsTecnicoDelete(tecnicoDeleteOptions.id);  // Cargar más opciones
    }
}

// Conectar el evento de scroll al `ul` para carga infinita
tecnicoDeleteOptions.addEventListener('scroll', loadMoreOptionsTecnicoDelete);

async function toggleOptionsTecnicoDelete(idInput, idOptions) {
    const input = document.getElementById(idInput);
    const optionsListUL = document.getElementById(idOptions);

    if (!optionsListUL || !input) {
        return;
    }
   
    if (optionsListUL.classList.contains('show')) {
        optionsListUL.classList.remove('show')
        return;
    }
   
    if (input.value === "") {
        if (optionsListUL.querySelectorAll('li').length === 0) {
            await loadPaginatedOptionsTecnicoDelete(idOptions);
        } 
    } else {
        filterOptionsTecnicoDelete(input, idOptions);
    }

    optionsListUL.classList.add('show');
}

async function loadPaginatedOptionsTecnicoDelete(idOptions) {
    const url = `${baseUrlMAIN}/dashboard-tecnicos/getPaginatedTecnicos?page=${currentPageTecnicoDelete}`;
    const optionsListUL = document.getElementById(idOptions);

    try {
        const response = await fetch(url);

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`${errorData.error} - ${errorData.details}`);
        }

        const data = await response.json();

        if (data.data == null) {
            const li = document.createElement('li');
            li.textContent = data.message;
            optionsListUL.innerHTML = "";
            optionsListUL.appendChild(li);
            return;
        }

        // Actualiza la lista con las opciones evitando duplicados
        populateOptionsListTecnicoDelete(optionsListUL, data.data);
        currentPageTecnicoDelete++;
    } catch (error) {
        //console.error("Error al cargar técnicos:", error.message);
    } finally {
        isLoadingTecnicoDelete = false;
    }
}

function populateOptionsListTecnicoDelete(optionsListUL, tecnicos) {
    const existingValues = new Set(
        Array.from(optionsListUL.children).map((li) => li.textContent.trim())
    );

    tecnicos.forEach((tecnico) => {
        const value = `${tecnico.idTecnico} | ${tecnico.nombreTecnico}`;
        const tecnicoData = JSON.stringify(tecnico);

        if (!existingValues.has(value)) {
            const li = document.createElement('li');
            li.textContent = value;
            li.setAttribute('onclick', `selectOptionTecnicoDelete('${value}', ${tecnicoData})`);
            optionsListUL.appendChild(li);

            // Agrega el nuevo valor al Set
            existingValues.add(value);
        }
    });
}

/* FIN de funciones para manejar el input dinámico */
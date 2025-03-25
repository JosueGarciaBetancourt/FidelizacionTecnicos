let tecnicoDisableInput = document.getElementById('tecnicoDisableInput');
let tecnicoDisableOptions = document.getElementById('tecnicoDisableOptions');
let celularDisableInput = document.getElementById('celularInputDisable');
let fechaNacimientoDisableInput = document.getElementById('fechaNacimientoInputDisable');
let puntosActualesDisableInput = document.getElementById('puntosActualesInputDisable');
let historicoPuntosDisableInput = document.getElementById('historicoPuntosInputDisable');
let rangoInputDisable = document.getElementById('rangoInputDisable');
let searchDisableTecnicoMessageError = document.getElementById('searchDisableTecnicoMessageError');
let disableTecnicoMessageError = document.getElementById('disableTecnicoMessageError');
let someHiddenIdInputsTecnicoDisableArray = ['idDisableTecnicoInput'];

let formTecnicoDisableInputsArray = [
	tecnicoDisableInput,
	celularDisableInput,
    fechaNacimientoDisableInput,
    puntosActualesDisableInput,
    historicoPuntosDisableInput,
    rangoInputDisable,
];

function validarCamposVaciosFormularioDisable() {
  let allFilled = true;
  formTecnicoDisableInputsArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function guardarModalInhabilitarTecnico(idModal, idForm) {
    if (validarCamposVaciosFormularioDisable()) {
		disableTecnicoMessageError.classList.remove("shown");
		guardarModal(idModal, idForm);	
    } else {
        disableTecnicoMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        disableTecnicoMessageError.classList.add("shown");
	}
}

function clearMultiSelectDropdownDisableTecnico() {
    const clearTagsMultiSelectDropdown = window.getExternFunction("idMultiSelectDropdownContainer_InhabilitarTecnico", "clearTagsMultiSelectDropdown");
    if (clearTagsMultiSelectDropdown) {
        clearTagsMultiSelectDropdown();
    }
}

function fillMultiSelectDropdownDisableTecnico(idNameOficioTecnico) {
    const idsNamesOficios = idNameOficioTecnico.split('|').map(item => item.trim()).join(', ');
    const fillTagsMultiSelectDropdown = window.getExternFunction("idMultiSelectDropdownContainer_InhabilitarTecnico", "fillTagsMultiSelectDropdown");
    
    if (fillTagsMultiSelectDropdown) {
        fillTagsMultiSelectDropdown(idsNamesOficios.split(',').map(item => item.trim()));
    }
}

/* INICIO Funciones para manejar el input dinámico */
let currentPageTecnicoDisable = 1;

function selectOptionTecnicoDisable(value, tecnico) {
    const hiddenIdTecnicoInput = document.getElementById(someHiddenIdInputsTecnicoDisableArray[0]);

    // Colocar en el input la opción seleccionada 
    selectOption(value, tecnicoDisableInput.id, tecnicoDisableOptions.id); 
    
    //consoleLogJSONItems(tecnico);
    
    if (!tecnico) {
        celularDisableInput.value = "";
        clearMultiSelectDropdownDisableTecnico();
        fechaNacimientoDisableInput.value = "";
        puntosActualesDisableInput.value = "";
        historicoPuntosDisableInput.value = "";
        rangoInputDisable.value = "";
        hiddenIdTecnicoInput.value  = "";
        return;
    }
   
    // Llenar los demás campos del formulario
    celularDisableInput.value = tecnico.celularTecnico;
    fillMultiSelectDropdownDisableTecnico(tecnico.idNameOficioTecnico);
    fechaNacimientoDisableInput.value = tecnico.fechaNacimiento_Tecnico;
    puntosActualesDisableInput.value = tecnico.totalPuntosActuales_Tecnico;
    historicoPuntosDisableInput.value = tecnico.historicoPuntos_Tecnico;
    rangoInputDisable.value = tecnico.nombre_Rango;

    // Llenar campos ocultos
    hiddenIdTecnicoInput.value = tecnico.idTecnico;
    searchDisableTecnicoMessageError.classList.remove("shown");
}

async function filterOptionsTecnicoDisable(input, idOptions) {
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
            console.warn(`Error en filterOptionsTecnicoDisable: ${errorData.message}`);
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
        li.setAttribute('onclick', `selectOptionTecnicoDisable('${value}', ${tecnicoData})`);
        ul.appendChild(li);
    });

    ul.classList.add('show');
}

async function validateValueOnRealTimeTecnicoDisable(input, idMessageError, otherInputsArray = null) {
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
        clearMultiSelectDropdownDisableTecnico();
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
            fillMultiSelectDropdownDisableTecnico(tecnicoBuscado.idNameOficioTecnico);
            document.getElementById(otherInputsArray[2]).value = tecnicoBuscado.fechaNacimiento_Tecnico;
            document.getElementById(otherInputsArray[3]).value = tecnicoBuscado.totalPuntosActuales_Tecnico;
            document.getElementById(otherInputsArray[4]).value = tecnicoBuscado.historicoPuntos_Tecnico;
            document.getElementById(otherInputsArray[5]).value = tecnicoBuscado.nombre_Rango;
        }
    } catch (error) {
        console.error(`Error inesperado en validateValueOnRealTimeTecnicoEdit: ${error.message}`);
        clearInputs();
    }
}

// Manejador del evento de scroll
async function loadMoreOptionsTecnicoDisable(event) {
    const optionsListUL = event.target;
    const threshold = 0.6; // 60% del contenido visible

    // Si el usuario ha llegado al final de la lista (se detecta el scroll)
    if (optionsListUL.scrollTop + optionsListUL.clientHeight >= optionsListUL.scrollHeight * threshold) {
        await loadPaginatedOptionsTecnicoDisable(tecnicoDisableOptions.id);  // Cargar más opciones
    }
}

// Conectar el evento de scroll al `ul` para carga infinita
tecnicoDisableOptions.addEventListener('scroll', loadMoreOptionsTecnicoDisable);

async function toggleOptionsTecnicoDisable(idInput, idOptions) {
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
            await loadPaginatedOptionsTecnicoDisable(idOptions);
        } 
    } else {
        filterOptionsTecnicoDisable(input, idOptions);
    }

    optionsListUL.classList.add('show');
}

async function loadPaginatedOptionsTecnicoDisable(idOptions) {
    const url = `${baseUrlMAIN}/dashboard-tecnicos/getPaginatedTecnicos?page=${currentPageTecnicoDisable}`;
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
        populateOptionsListTecnicoDisable(optionsListUL, data.data);
        currentPageTecnicoDisable++;
    } catch (error) {
        //console.error("Error al cargar técnicos:", error.message);
    } finally {
        isLoadingTecnicoDisable = false;
    }
}

function populateOptionsListTecnicoDisable(optionsListUL, tecnicos) {
    const existingValues = new Set(
        Array.from(optionsListUL.children).map((li) => li.textContent.trim())
    );

    tecnicos.forEach((tecnico) => {
        const value = `${tecnico.idTecnico} | ${tecnico.nombreTecnico}`;
        const tecnicoData = JSON.stringify(tecnico);

        if (!existingValues.has(value)) {
            const li = document.createElement('li');
            li.textContent = value;
            li.setAttribute('onclick', `selectOptionTecnicoDisable('${value}', ${tecnicoData})`);
            optionsListUL.appendChild(li);

            // Agrega el nuevo valor al Set
            existingValues.add(value);
        }
    });
}

/* FIN de funciones para manejar el input dinámico */
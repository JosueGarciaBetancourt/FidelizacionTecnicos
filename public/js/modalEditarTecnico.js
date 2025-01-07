let tecnicoEditInput = document.getElementById('tecnicoEditInput');
let tecnicoEditOptions = document.getElementById('tecnicoEditOptions');
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
let someHiddenIdInputsTecnicoEditArray = ['idEditTecnicoInput', 'idsOficioEditArrayInput'];


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

/* INICIO Funciones para manejar el input dinámico */

let currentPageTecnicoEdit = 1; // Página actual
let isLoadingTecnicoEdit = false;

function selectOptionEditarTecnico(value, tecnico) {
    const hiddenIdTecnicoInput = document.getElementById(someHiddenIdInputsTecnicoEditArray[0]);
    const hiddenIdsOficioTecnicoInput = document.getElementById(someHiddenIdInputsTecnicoEditArray[1]);

    // Colocar en el input la opción seleccionada 
    selectOption(value, tecnicoEditInput.id, tecnicoEditOptions.id); 
    
    //consoleLogJSONItems(tecnico);
    
    if (!tecnico) {
        celularEditInput.value = "";
        oficioEditInput.value = "";
        fechaNacimientoEditInput.value = "";
        puntosActualesEditInput.value = "";
        historicoPuntosEditInput.value = "";
        rangoInputEdit.value = "";
        hiddenIdTecnicoInput.value  = "";
        hiddenIdsOficioTecnicoInput.value = "";
        return;
    }
   
    // Llenar los demás campos del formulario
    celularEditInput.value = tecnico.celularTecnico;
    oficioEditInput.value = tecnico.idNameOficioTecnico;
    fechaNacimientoEditInput.value = tecnico.fechaNacimiento_Tecnico;
    puntosActualesEditInput.value = tecnico.totalPuntosActuales_Tecnico;
    historicoPuntosEditInput.value = tecnico.historicoPuntos_Tecnico;
    rangoInputEdit.value = tecnico.rangoTecnico;

    // Llenar campos ocultos
    hiddenIdTecnicoInput.value = tecnico.idTecnico;
    hiddenIdsOficioTecnicoInput.value = tecnico.idsOficioTecnico;
    searchEditTecnicoMessageError.classList.remove("shown");
}

async function filterOptionsTecnicoEdit(input, idOptions) {
    const filter = input.value.trim().toUpperCase();
    const ul = document.getElementById(idOptions);
    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`;  
    const url = `${baseUrl}/dashboard-tecnicos/getFilteredTecnicos`;

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
            console.warn(`Error en filterOptionsTecnicoEdit: ${errorData.message}`);
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
        li.setAttribute('onclick', `selectOptionEditarTecnico('${value}', ${tecnicoData})`);
        ul.appendChild(li);
    });

    ul.classList.add('show');
}

async function validateValueOnRealTimeTecnicoEdit(input, idMessageError, someHiddenIdInputsArray = null, otherInputsArray = null) {
    const idNombreTecnico = input.value.trim();
    const messageError = document.getElementById(idMessageError);
    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`;
    const url = `${baseUrl}/dashboard-tecnicos/getTecnicoByIdNombre`;

    const clearHiddenInputs = () => {
        if (Array.isArray(someHiddenIdInputsArray)) {
            someHiddenIdInputsArray.forEach(idInput => {
                const inputElement = document.getElementById(idInput);
                if (inputElement) {
                    inputElement.value = ""; // Asignar valor vacío
                }
            });
        }
    };

    const clearInputs = () => {
        clearHiddenInputs();
        if (Array.isArray(otherInputsArray)) {
            otherInputsArray.forEach(idOtherInput => {
                const otherInputElement = document.getElementById(idOtherInput);
                if (otherInputElement) {
                    otherInputElement.value = "";
                }
            });
        }
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

        // Actualizar los inputs ocultos
        if (someHiddenIdInputsArray) {
            document.getElementById(someHiddenIdInputsArray[0]).value = tecnicoBuscado.idTecnico;
            document.getElementById(someHiddenIdInputsArray[1]).value = tecnicoBuscado.idsOficioTecnico;
        }

        // Rellenar otros inputs visibles si se requiere
        if (otherInputsArray) {
            document.getElementById(otherInputsArray[0]).value = tecnicoBuscado.celularTecnico;
            document.getElementById(otherInputsArray[1]).value = tecnicoBuscado.idNameOficioTecnico;
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
async function loadMoreOptionsTecnicoEdit(event) {
    const optionsListUL = event.target;
    const threshold = 0.6; // 60% del contenido visible

    // Si el usuario ha llegado al final de la lista (se detecta el scroll)
    if (optionsListUL.scrollTop + optionsListUL.clientHeight >= optionsListUL.scrollHeight * threshold) {
        await loadPaginatedOptionsTecnicoEdit(tecnicoEditOptions.id);  // Cargar más opciones
    }
}

// Conectar el evento de scroll al `ul` para carga infinita
tecnicoEditOptions.addEventListener('scroll', loadMoreOptionsTecnicoEdit);

async function toggleOptionsTecnicoEdit(idInput, idOptions) {
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
            await loadPaginatedOptionsTecnicoEdit(idOptions);
        } 
    } else {
        filterOptionsTecnicoEdit(input, idOptions);
    }

    optionsListUL.classList.add('show');
}

async function loadPaginatedOptionsTecnicoEdit(idOptions) {
    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`;
    const url = `${baseUrl}/dashboard-tecnicos/getPaginatedTecnicos?page=${currentPageTecnicoEdit}`;
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
        
        /* if (data.current_page === data.last_page) {
            // Actualiza la lista con las opciones evitando duplicados
            populateOptionsListTecnicoEdit(optionsListUL, data.data);
            return;
        } */
        
        // Actualiza la lista con las opciones evitando duplicados
        populateOptionsListTecnicoEdit(optionsListUL, data.data);
        currentPageTecnicoEdit++;
    } catch (error) {
        //console.error("Error al cargar técnicos:", error.message);
    } finally {
        isLoadingTecnicoDelete = false;
    }
}

/* function populateOptionsListTecnicoEdit(optionsListUL, tecnicos) {
    tecnicos.forEach((tecnico) => {
        const value = `${tecnico.idTecnico} | ${tecnico.nombreTecnico}`;
        const tecnicoData = JSON.stringify(tecnico);
        const li = document.createElement('li');

        li.textContent = value;
        li.setAttribute('onclick', `selectOptionEditarTecnico('${value}', ${tecnicoData})`);
        optionsListUL.appendChild(li);
    });
} */

function populateOptionsListTecnicoEdit(optionsListUL, tecnicos) {
    const existingValues = new Set(
        Array.from(optionsListUL.children).map((li) => li.textContent.trim())
    );

    tecnicos.forEach((tecnico) => {
        const value = `${tecnico.idTecnico} | ${tecnico.nombreTecnico}`;
        const tecnicoData = JSON.stringify(tecnico);

        if (!existingValues.has(value)) {
            const li = document.createElement('li');
            li.textContent = value;
            li.setAttribute('onclick', `selectOptionEditarTecnico('${value}', ${tecnicoData})`);
            optionsListUL.appendChild(li);

            // Agrega el nuevo valor al Set
            existingValues.add(value);
        }
    });
}

/* FIN de funciones para manejar el input dinámico */


let tecnicoDeleteInput = document.getElementById('tecnicoDeleteInput');
let tecnicoDeleteOptions = document.getElementById('tecnicoDeleteOptions');
let celularDeleteInput = document.getElementById('celularInputDelete');
let oficioDeleteInput = document.getElementById('oficioInputDelete');
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
	oficioDeleteInput,
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

/* INICIO Funciones para manejar el input dinámico */
function selectOptionTecnicoDelete(value, tecnico) {
    const hiddenIdTecnicoInput = document.getElementById(someHiddenIdInputsTecnicoDeleteArray[0]);

    // Colocar en el input la opción seleccionada 
    selectOption(value, tecnicoDeleteInput.id, tecnicoDeleteOptions.id); 
    
    //consoleLogJSONItems(tecnico);
    
    if (!tecnico) {
        celularDeleteInput.value = "";
        oficioDeleteInput.value = "";
        fechaNacimientoDeleteInput.value = "";
        puntosActualesDeleteInput.value = "";
        historicoPuntosDeleteInput.value = "";
        rangoInputDelete.value = "";
        hiddenIdTecnicoInput.value  = "";
        return;
    }
   
    // Llenar los demás campos del formulario
    celularDeleteInput.value = tecnico.celularTecnico;
    oficioDeleteInput.value = tecnico.idNameOficioTecnico;
    fechaNacimientoDeleteInput.value = tecnico.fechaNacimiento_Tecnico;
    puntosActualesDeleteInput.value = tecnico.totalPuntosActuales_Tecnico;
    historicoPuntosDeleteInput.value = tecnico.historicoPuntos_Tecnico;
    rangoInputDelete.value = tecnico.rangoTecnico;

    // Llenar campos ocultos
    hiddenIdTecnicoInput.value = tecnico.idTecnico;
    searchDeleteTecnicoMessageError.classList.remove("shown");
}

async function filterOptionsTecnicoDelete(idInput, idOptions) {
    const input = document.getElementById(idInput);
    const filter = input.value.trim().toUpperCase();
    const ul = document.getElementById(idOptions);

    // Si el campo de búsqueda está vacío, no hacemos la solicitud
    if (filter === "") {
        ul.classList.remove('show');
        return;
    }

    try {
        // Llamada al servidor para buscar técnicos que coincidan con el filtro
        const baseUrl = `${window.location.origin}`;  // Ajusta la URL base según corresponda
        const url = `${baseUrl}/dashboard-tecnicos/getFilteredTecnicos`;  // Añadir filtro como parámetro

        const response = await fetch(url, {
            method: 'POST', // Cambiado a POST para enviar datos
            headers: {
                'Content-Type': 'application/json', // Importante para enviar JSON
                'X-CSRF-TOKEN': csrfTokenMAIN, // Agregar el token CSRF aquí
            },
            body: JSON.stringify({ filter: filter }), // Enviando el comentario
        });

        // Verificar el estado de la respuesta
        if (!response.ok) {
            // Si no es una respuesta exitosa, mostrar el código de estado
            throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
        }

        // Verificar que la respuesta sea tipo JSON
        const contentType = response.headers.get('Content-Type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text(); // Leer la respuesta como texto si no es JSON
            console.error("Respuesta inesperada (no JSON):", text);
            throw new Error('Se esperaba una respuesta JSON, pero se recibió otro tipo de contenido.');
        }

        // Intentar parsear la respuesta JSON
        const data = await response.json();

        console.log(data);
        
        // Limpiar las opciones anteriores
        ul.innerHTML = "";

        // Verificar si hay resultados
        if (data.data && data.data.length > 0) {
            // Mostrar las opciones y agregarlas dinámicamente
            data.data.forEach(tecnico => {
                const li = document.createElement('li');
                const value = `${tecnico.idTecnico} | ${tecnico.nombreTecnico}`;
                const tecnicoData = JSON.stringify(tecnico);
                
                li.textContent = value;
                li.setAttribute('onclick', `selectOptionTecnicoDelete('${value}', ${tecnicoData})`);
                ul.appendChild(li);
            });
        } else {
            // Si no hay resultados, mostrar un mensaje o hacer algo
            const li = document.createElement('li');
            li.textContent = 'No se encontraron técnicos';
            ul.appendChild(li);
        }
        
        ul.classList.add('show');
    } catch (error) {
        console.error(error.message);
    }
}

async function validateValueOnRealTimeTecnicoDelete(input, idMessageError, someHiddenIdInputsArray=null, otherInputsArray=null) {
    const idNombreTecnico = input.value.trim();
    const messageError = document.getElementById(idMessageError);
    const baseUrl = `${window.location.origin}`;
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

    const response = await fetch(url, {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfTokenMAIN, 
        },
        body: JSON.stringify({ idNombreTecnico: idNombreTecnico }), 
    });

    if (!response.ok) {
        clearInputs();
        messageError.classList.add('shown');
        //const errorData = await response.json();
        //throw new Error(`Error: ${errorData.error} - ${errorData.details}`);
    }

    const data = await response.json()

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
}

let currentPageTecnicoDelete = 1; 
let isLoadingTecnicoDelete = false;

// Manejador del evento de scroll
async function loadMoreOptionsTecnicoDelete(event) {
    const optionsListUL = event.target;
    const threshold = 0.6; // 60% del contenido visible

    // Si el usuario ha llegado al final de la lista (se detecta el scroll)
    if (optionsListUL.scrollTop + optionsListUL.clientHeight >= optionsListUL.scrollHeight * threshold) {
        // Solo cargar más si no se están haciendo otras solicitudes
        if (!isLoadingTecnicoDelete) {
            await loadOptionsTecnicoDelete(optionsListUL.id);  // Cargar más opciones
        }
    }
}

async function toggleOptionsTecnicoDelete(idInput, idOptions) {
    const input = document.getElementById(idInput);
    const optionsListUL = document.getElementById(idOptions);

    if (!optionsListUL || !input) {
        return;
    }

    if (optionsListUL.classList.contains('show')) {
        optionsListUL.classList.remove('show');
        return;
    }

    if (input.value === "") {
        // Realiza la solicitud solo si la lista está vacía
        await loadOptionsTecnicoDelete(idOptions);
        optionsListUL.classList.add('show');

        // Conectar el evento de scroll al `ul` para carga infinita
        optionsListUL.addEventListener('scroll', loadMoreOptionsTecnicoDelete);
    } else {
        filterOptionsTecnicoDelete(idInput, idOptions);
    }
}

async function loadOptionsTecnicoDelete(idOptions) {
    if (isLoadingTecnicoDelete) return;  // Evita múltiples solicitudes simultáneas
    isLoadingTecnicoDelete = true;

    const baseUrl = `${window.location.origin}`;
    const url = `${baseUrl}/dashboard-tecnicos/getAllTecnicos?page=${currentPageTecnicoDelete}`;

    try {
        const response = await fetch(url);

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(`${errorData.error} - ${errorData.details}`);
        }

        const data = await response.json();

        if (!data.data || data.data.length === 0) {
            throw new Error("No hay más técnicos disponibles");
        }

        // Renderiza las opciones dinámicamente
        const optionsListUL = document.getElementById(idOptions);
        populateOptionsListTecnicoDelete(optionsListUL, data.data);

        // Incrementa la página para la siguiente solicitud
        currentPageTecnicoDelete++;
    } catch (error) {
        console.error("Error al cargar técnicos:", error.message);
    } finally {
        isLoadingTecnicoDelete = false;
    }
}

function populateOptionsListTecnicoDelete(optionsListUL, tecnicos) {
    tecnicos.forEach((tecnico) => {
        const value = `${tecnico.idTecnico} | ${tecnico.nombreTecnico}`;
        const tecnicoData = JSON.stringify(tecnico);
        const li = document.createElement('li');

        li.textContent = value;
        li.setAttribute('onclick', `selectOptionTecnicoDelete('${value}', ${tecnicoData})`);
        optionsListUL.appendChild(li);
    });
}

/* FIN de funciones para manejar el input dinámico */
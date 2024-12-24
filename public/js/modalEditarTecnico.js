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
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

/*
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
*/

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

async function filterOptionsTecnicoEdit(idInput, idOptions) {
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
        const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`;  // Ajusta la URL base según corresponda
        const url = `${baseUrl}/dashboard-tecnicos/getFilteredTecnicos`;  // Añadir filtro como parámetro

        const response = await fetch(url, {
            method: 'POST', // Cambiado a POST para enviar datos
            headers: {
                'Content-Type': 'application/json', // Importante para enviar JSON
                'X-CSRF-TOKEN': csrfToken, // Agregar el token CSRF aquí
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
                li.setAttribute('onclick', `selectOptionEditarTecnico('${value}', ${tecnicoData})`);
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

async function validateValueOnRealTimeTecnicoEdit(input, idMessageError, someHiddenIdInputsArray=null, otherInputsArray=null) {
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

    const response = await fetch(url, {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken, 
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
}

let currentPage = 1;  // Página inicial
let isLoading = false; // Evita hacer múltiples solicitudes al mismo tiempo

// Manejador del evento de scroll
async function loadMoreOptions(event) {
    const optionsListUL = event.target;
    const threshold = 0.6; // 60% del contenido visible

    // Si el usuario ha llegado al final de la lista (se detecta el scroll)
    if (optionsListUL.scrollTop + optionsListUL.clientHeight >= optionsListUL.scrollHeight * threshold) {
        // Solo cargar más si no se están haciendo otras solicitudes
        if (!isLoading) {
            await loadOptions(optionsListUL.id);  // Cargar más opciones
        }
    }
}

async function toggleOptionsTecnicoEdit(idInput, idOptions) {
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
        await loadOptions(idOptions);
        optionsListUL.classList.add('show');

        // Conectar el evento de scroll al `ul` para carga infinita
        optionsListUL.addEventListener('scroll', loadMoreOptions);
    } else {
        filterOptionsTecnicoEdit(idInput, idOptions);
    }
}

async function loadOptions(idOptions) {
    if (isLoading) return;  // Evita múltiples solicitudes simultáneas
    isLoading = true;

    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`;
    const url = `${baseUrl}/dashboard-tecnicos/getAllTecnicos?page=${currentPage}`;

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
        populateOptionsList(optionsListUL, data.data);

        // Incrementa la página para la siguiente solicitud
        currentPage++;
    } catch (error) {
        console.error("Error al cargar técnicos:", error.message);
    } finally {
        isLoading = false;
    }
}

function populateOptionsList(optionsListUL, tecnicos) {
    tecnicos.forEach((tecnico) => {
        const value = `${tecnico.idTecnico} | ${tecnico.nombreTecnico}`;
        const tecnicoData = JSON.stringify(tecnico);
        const li = document.createElement('li');

        li.textContent = value;
        li.setAttribute('onclick', `selectOptionEditarTecnico('${value}', ${tecnicoData})`);
        optionsListUL.appendChild(li);
    });
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


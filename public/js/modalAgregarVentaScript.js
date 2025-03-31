let ventaIntermediadaObject = {};
let tecnicoAgregarVentaInput = document.getElementById('tecnicoInput');
let tecnicoAgregarVentaOptions = document.getElementById('tecnicoOptions');
let idVentaIntermediadaInput = document.getElementById('idVentaIntermediadaInput');
let idTecnicoInput = document.getElementById('idTecnicoInput');
let nombreTecnicoInput = document.getElementById('nombreTecnicoInput');
let tipoCodigoClienteInput = document.getElementById('tipoCodigoClienteInput');
let codigoClienteInput = document.getElementById('idClienteInput');
let nombreClienteInput = document.getElementById('nombreClienteInput');
let fechaEmisionVentaIntermediadaInput = document.getElementById('fechaEmisionVentaIntermediadaInput');
let horaEmisionVentaIntermediadaInput = document.getElementById('horaEmisionVentaIntermediadaInput');
let fechaHoraEmisionInput = document.getElementById('fechaHoraEmisionVentaIntermediadaInput');
let montoTotalInput = document.getElementById('montoTotalInput');
let puntosGanadosInput = document.getElementById('puntosGanadosInput');
let diasTranscurridosVentaHastaHoy = 0;

let formInputsArrayAgregarVenta = [
    idVentaIntermediadaInput, 
    idTecnicoInput, 
    nombreTecnicoInput, 
    tipoCodigoClienteInput,
    codigoClienteInput,
    nombreClienteInput,
    fechaHoraEmisionInput,
    montoTotalInput,
    puntosGanadosInput,
];

let tecnicoTooltip = document.getElementById('idTecnicoTooltip');
let codigoClienteTooltip = document.getElementById('idCodigoClienteTooltip');
let numComprobanteTooltip = document.getElementById('idNumComprobanteTooltip');
let fechaEmisionTooltip = document.getElementById('idFechaEmisionTooltip');
let horaEmisionTooltip = document.getElementById('idHoraEmisionTooltip');
let multiMessageError2 = document.getElementById('multiMessageError2');
let nuevaVentaMessageError = document.getElementById('nuevaVentaMessageError');

/* INICIO Funciones para manejar el input dinámico */
let currentPageAgregarVenta = 1; // Página actual

function selectOptionTecnicosAgregarVenta(value, tecnico) {
    // Colocar en el input la opción seleccionada 
    selectOption(value, tecnicoAgregarVentaInput.id, tecnicoAgregarVentaOptions.id); 
    
    //consoleLogJSONItems(tecnico);
    
    if (!tecnico) {
        idTecnicoInput.value = "";
        nombreTecnicoInput.value = "";
        return;
    }
   
    // Llenar campos ocultos
    idTecnicoInput.value = tecnico.idTecnico;
    nombreTecnicoInput.value = tecnico.nombreTecnico;
    nuevaVentaMessageError.classList.remove("shown");
}

async function filterOptionsTecnicosAgregarVenta(input, idOptions) {
    const filter = input.value.trim().toUpperCase();
    const ul = document.getElementById(idOptions);
    const url = `${baseUrlMAIN}/dashboard-tecnicos/getFilteredTecnicos`;

    if (!filter) {
        currentPageAgregarVenta = 1;
        ul.innerHTML = "";
        loadPaginatedOptionsTecnicosAgregarVenta(idOptions);
        return;
    }

    const response = await fetch(url, {
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfTokenMAIN,
        },
        body: JSON.stringify({ filter: filter, pageSize: 9}),
    });

    // Limpiar las opciones anteriores
    ul.innerHTML = "";

    // Verificar el estado de la respuesta
    if (!response.ok) {
        const errorData = await response.json();
        if (errorData.data == "") {
            console.warn(`Error en filterOptionsTecnicosAgregarVenta: ${errorData.message}`);
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
        li.setAttribute('onclick', `selectOptionTecnicosAgregarVenta('${value}', ${tecnicoData})`);
        ul.appendChild(li);
    });

    ul.classList.add('show');
}

async function validateValueOnRealTimeTecnicosAgregarVenta(input, idMessageError, someHiddenIdInputsArray = null) {
    const idNombreTecnico = input.value.trim();
    const messageError = document.getElementById(idMessageError);
    const url = `${baseUrlMAIN}/dashboard-tecnicos/getTecnicoByIdNombre`;

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

    if (idNombreTecnico === "") {
        messageError.classList.remove('shown');
        clearHiddenInputs();
        return;
    }

    // Validar que el formato sea válido
    const regex = /^\d+\s\|\s.+$/;

    if (!regex.test(idNombreTecnico)) {
        messageError.classList.add('shown');
        clearHiddenInputs();
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
            clearHiddenInputs();
            return;
        }

        const data = await response.json();

        if (!data.tecnicoBuscado) {
            clearHiddenInputs();
            messageError.classList.add('shown');
            return;
        }

        messageError.classList.remove('shown');

        // Obtener el objeto tecnicoBuscado de la respuesta JSON
        const tecnicoBuscado = data.tecnicoBuscado;

        // Actualizar los inputs ocultos
        if (someHiddenIdInputsArray) {
            document.getElementById(someHiddenIdInputsArray[0]).value = tecnicoBuscado.idTecnico;
            document.getElementById(someHiddenIdInputsArray[1]).value = tecnicoBuscado.nombreTecnico;
        }
    } catch (error) {
        console.error(`Error inesperado en validateValueOnRealTimeTecnicosAgregarVenta: ${error.message}`);
        clearHiddenInputs();
    }
}

// Manejador del evento de scroll
async function loadMoreOptionsTecnicosAgregarVenta(event) {
    const optionsListUL = event.target;
    const threshold = 0.6; // 60% del contenido visible

    // Si el usuario ha llegado al final de la lista (se detecta el scroll)
    if (optionsListUL.scrollTop + optionsListUL.clientHeight >= optionsListUL.scrollHeight * threshold) {
        await loadPaginatedOptionsTecnicosAgregarVenta(tecnicoAgregarVentaOptions.id);  // Cargar más opciones
    }
}

// Conectar el evento de scroll al `ul` para carga infinita
tecnicoAgregarVentaOptions.addEventListener('scroll', loadMoreOptionsTecnicosAgregarVenta);

async function toggleOptionsTecnicosAgregarVenta(input, idOptions) {
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
            await loadPaginatedOptionsTecnicosAgregarVenta(idOptions);
        } 
    } else {
        filterOptionsTecnicosAgregarVenta(input, idOptions);
    }

    optionsListUL.classList.add('show');
}

async function loadPaginatedOptionsTecnicosAgregarVenta(idOptions) {
    const url = `${baseUrlMAIN}/dashboard-tecnicos/getPaginatedTecnicos?page=${currentPageAgregarVenta}&pageSize=9`;
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
            optionsListUL.appendChild(li);
            return;
        }

        // Actualiza la lista con las opciones evitando duplicados
        populateOptionsListTecnicosAgregarVenta(optionsListUL, data.data);
        currentPageAgregarVenta++;
    } catch (error) {
        //console.error("Error al cargar técnicos:", error.message);
    }
}

function populateOptionsListTecnicosAgregarVenta(optionsListUL, tecnicos) {
    const existingValues = new Set(
        Array.from(optionsListUL.children).map((li) => li.textContent.trim())
    );

    tecnicos.forEach((tecnico) => {
        const value = `${tecnico.idTecnico} | ${tecnico.nombreTecnico}`;
        const tecnicoData = JSON.stringify(tecnico);

        if (!existingValues.has(value)) {
            const li = document.createElement('li');
            li.textContent = value;
            li.setAttribute('onclick', `selectOptionTecnicosAgregarVenta('${value}', ${tecnicoData})`);
            optionsListUL.appendChild(li);

            // Agrega el nuevo valor al Set
            existingValues.add(value);
        }
    });
}
/* FIN de funciones para manejar el input dinámico */

function analizarXML(file) {
    /*
    HACER PRUEBA UNITARIA PARA ESTA FUNCIÓN 
    */

    if (tecnicoAgregarVentaInput.value == '' || tecnicoAgregarVentaInput.value == null) {
        multiMessageError2.textContent = "Seleccione primero un técnico.";
        multiMessageError2.classList.add('shown');
        return;
    }

    multiMessageError2.classList.remove('shown');

    const reader = new FileReader();

    reader.onload = function(event) {
        const xmlText = event.target.result;

        // Parsear el contenido XML en un documento DOM
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xmlText, "application/xml");

        // Obtener valores
        const idVentaIntermediada = getElementText(xmlDoc, 'cbc', 'ID');

        const cliente = {
			codigoCliente: getElementText(xmlDoc, 'cbc', 'ID', 'cac:AccountingCustomerParty', 'cac:Party', 'cac:PartyIdentification'),
			nombreCliente: getElementText(xmlDoc, 'cbc', 'RegistrationName', 'cac:AccountingCustomerParty', 'cac:Party', 'cac:PartyLegalEntity')
		};

        const fechaHoraEmision = {
            fecha: getElementText(xmlDoc, 'cbc', 'IssueDate'),
            hora: getElementText(xmlDoc, 'cbc', 'IssueTime')
        };

        const montoTotal = getElementText(xmlDoc, 'cbc', 'PayableAmount');

        // Detectar tipo de código cliente
        const tipoCodigoCliente = detectarTipoCodigoCliente(cliente.codigoCliente);

		// Crear un array con los valores
        ventaIntermediadaObject = {
            idVentaIntermediada: idVentaIntermediada,
            tipoCodigoCliente: tipoCodigoCliente,
            clienteCodigo: cliente.codigoCliente,
            clienteNombre: cliente.nombreCliente,
            fechaEmision: fechaHoraEmision.fecha,
            horaEmision: fechaHoraEmision.hora,
            montoTotal: montoTotal,
            puntosGanados: Math.round(parseFloat(montoTotal)),
        }

        consoleLogJSONItems(ventaIntermediadaObject);

        if (!idTecnicoInput.value.trim() || !nombreTecnicoInput.value.trim()) {
            //console.log("Tiene que rellenar el campo Técnico primero");
            showHideTooltip(tecnicoTooltip, "Seleccione un técnico primero");
            clearSomeHiddenInputs();
        } else {
            multiMessageError2.classList.remove("shown");
            fillSomeHiddenInputs(ventaIntermediadaObject);
        }
    };

    reader.onerror = function(event) {
        console.error('Error al leer el archivo:', event.target.error);
    };

    reader.readAsText(file);
}

function clearSomeHiddenInputs() {
    formInputsArrayAgregarVenta.forEach(input => {
        if (input) {
            input.value = "";
        }
    });
}

function fillWithZerosIdVentaIntermediada(idVentaIntermediada) {
    // Dividir el identificador en la parte antes y después del guion
    const [prefix, suffix] = idVentaIntermediada.split('-');

    // Rellenar la parte después del guion con ceros hasta alcanzar 8 caracteres
    const filledSuffix = suffix.padStart(8, '0');

    // Recombinar las partes
    return `${prefix}-${filledSuffix}`;
}

function fillSomeHiddenInputs(ventaIntermediadaObject) {
    idVentaIntermediadaInput.value = fillWithZerosIdVentaIntermediada(ventaIntermediadaObject.idVentaIntermediada) || '';
    tipoCodigoClienteInput.value = ventaIntermediadaObject.tipoCodigoCliente || '';
    codigoClienteInput.value = ventaIntermediadaObject.clienteCodigo || '';
    nombreClienteInput.value = ventaIntermediadaObject.clienteNombre || '';
    fechaEmisionVentaIntermediadaInput.value = ventaIntermediadaObject.fechaEmision;
    horaEmisionVentaIntermediadaInput.value = ventaIntermediadaObject.horaEmision;
    fechaHoraEmisionInput.value = ventaIntermediadaObject.fechaEmision + " " + ventaIntermediadaObject.horaEmision || '';
    montoTotalInput.value = ventaIntermediadaObject.montoTotal || '';
    puntosGanadosInput.value = ventaIntermediadaObject.puntosGanados || ''; 
}

function getElementText(xmlDoc, prefix, tagName, ...path) {
    const namespaces = {
        cbc: 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2',
        cac: 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2'
    };

    let element = xmlDoc.documentElement;

    // Navegar a través de los elementos
    for (let step of path) {
        const stepPrefix = step.split(':')[0];
        const stepName = step.split(':')[1] || step;
        element = element.getElementsByTagNameNS(namespaces[stepPrefix] || '', stepName)[0];
        if (!element) {
            console.log(`No se encontró el elemento: ${step}`);
            return '';
        }
    }

    // Obtener el elemento final
    const finalElement = element.getElementsByTagNameNS(namespaces[prefix] || '', tagName)[0];
    if (!finalElement) {
        console.log(`No se encontró el elemento final: ${tagName}`);
        return '';
    }

    return finalElement.textContent.trim();
}

function detectarTipoCodigoCliente(codigoCliente) {
    return codigoCliente.length === 8 ? 'DNI' : codigoCliente.length === 11 ? 'RUC' : 'Desconocido';
}

function limitSpecificCharacters(input, characterLimits) {
    const originalValue = input.value;
    const cursorPos = input.selectionStart;
    let newValue = '';
    let charCounts = {};

    // Inicializar los contadores de caracteres
    for (const char in characterLimits) {
        charCounts[char] = 0;
    }

    // Iterar sobre cada carácter en el valor de entrada
    for (let i = 0; i < originalValue.length; i++) {
        const char = originalValue[i];

        if (char in characterLimits) {
            if (charCounts[char] < characterLimits[char]) {
                newValue += char;
                charCounts[char]++;
            }
        } else {
            newValue += char;
        }
    }

    // Actualizar el valor del input solo si ha cambiado
    if (newValue !== originalValue) {
        input.value = newValue;

        // Calcular la nueva posición del cursor
        const maxNewCursorPosition = Math.min(newValue.length, cursorPos);
        
        // Mover el cursor a la posición anterior o la más cercana posible
        if (originalValue.length !== cursorPos) {
            input.setSelectionRange(maxNewCursorPosition - 1, maxNewCursorPosition - 1);
        } else {
            input.setSelectionRange(maxNewCursorPosition, maxNewCursorPosition);
        }
    }
}

function keepWantedCharacters(input, charactersArray) {
    // Obtener el valor del input y la posición del cursor actual
    const value = input.value;
    const valueLength = input.value.length;
    const cursorPos = input.selectionStart; // Posición del cursor antes del filtrado

    // Crear una expresión regular que coincida con los caracteres no deseados
    const escapedCharacters = charactersArray.map(char => char.replace(/[-[\]/{}()*+?.\\^$|]/g, '\\$&')).join('');
    const regex = new RegExp(`[^${escapedCharacters}]`, 'g'); // Coincide con caracteres NO deseados

    // Eliminar todos los caracteres no deseados
    let newValue = value.replace(regex, '');

    // Actualizar el campo de entrada con el valor filtrado
    if (newValue !== value) {
        input.value = newValue;
        const newCursorPos = cursorPos;

        // Verificar que la nueva posición del cursor no exceda la longitud del nuevo valor
        const maxNewCursorPosition = Math.min(newValue.length, newCursorPos);
        
        // Mover el cursor a la posición anterior o la más cercana posible
        if (valueLength !== cursorPos) {
            input.setSelectionRange(maxNewCursorPosition-1, maxNewCursorPosition-1);
        } else {
            input.setSelectionRange(maxNewCursorPosition, maxNewCursorPosition);
        }
    }   
}

let numComprobanteIsValid = true;

function validateNumComprobanteInput(numComprobanteInput) {
    // Convertir el valor del campo de entrada a mayúsculas
    numComprobanteInput.value = numComprobanteInput.value.toUpperCase();

    // Definir los caracteres permitidos y sus límites
    const wantedCharacters = ['B', 'F', '-', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    const characterLimits = {
        'B': 1,
        'F': 1,
        '-': 1,
    };

    // Filtrar los caracteres permitidos y limitar los caracteres según el objeto characterLimits
    keepWantedCharacters(numComprobanteInput, wantedCharacters);
    limitSpecificCharacters(numComprobanteInput, characterLimits);

    // Expresión regular mejorada para validar el formato F001-00000096 o B001-00000096
    const regex = /^[BF]\d{3}-\d{8}$/;
    const value = numComprobanteInput.value.trim();

    // Verificar si el valor coincide con el formato
    if (!regex.test(value)) {
        numComprobanteIsValid = false;
        numComprobanteTooltip.classList.remove('green');
        numComprobanteTooltip.classList.add('red');
        showHideTooltip(numComprobanteTooltip, "Número de comprobante con formato inválido. Debe seguir la forma: F001-00000096 ó B001-00000096");
        return;
    }

    numComprobanteTooltip.classList.remove('red');
    numComprobanteTooltip.classList.add('green');
    showHideTooltip(numComprobanteTooltip, "Número de comprobante con formato válido");
    numComprobanteIsValid = true;
}

let fechaEmisionIsValid = true;

function validateManualDateInput(dateInput) {
    const wantedCharacters = ['-', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    const characterLimits = {
        '-': 2,
    };

    keepWantedCharacters(dateInput, wantedCharacters)
    limitSpecificCharacters(dateInput, characterLimits)

    // Expresión regular para validar el formato AAAA-MM-DD
    const dateFormatRegex = /^(\d{4})-(\d{2})-(\d{2})$/;
    const value = dateInput.value.trim();

    // Verificar que el primer carácter del año, mes y día no sea 0
    if (value.startsWith('0')) {
        fechaEmisionTooltip.classList.remove('green');
        fechaEmisionTooltip.classList.add('red');
        showHideTooltip(fechaEmisionTooltip, "El primer carácter no puede ser 0.");
        fechaEmisionIsValid = false;
        return;
    }

    // Extraer año, mes y día de la fecha
    const match = value.match(dateFormatRegex);
    if (!match) {
        fechaEmisionTooltip.classList.remove('green');
        fechaEmisionTooltip.classList.add('red');
        showHideTooltip(fechaEmisionTooltip, "Formato de fecha inválido. Debe ser AAAA-MM-DD.");
        fechaEmisionIsValid = false;
        return;
    }
    
    const [_, year, month, day] = match;

    // Crear un objeto Date con el año, mes y día
    const inputDate = new Date(year, month - 1, day);
    const now = new Date();
    now.setHours(0, 0, 0, 0); // Ajustar la hora de la fecha actual para que sea medianoche

    // Verificar si la fecha es válida
    if (inputDate.getFullYear() === parseInt(year, 10) &&
        inputDate.getMonth() === parseInt(month, 10) - 1 && //Enero 0, Febrero 1, etc.
        inputDate.getDate() === parseInt(day, 10)
    ) {
        // Verificar si la fecha no es mayor que la fecha actual
        if (inputDate > now) {
            fechaEmisionTooltip.classList.remove('green');
            fechaEmisionTooltip.classList.add('red');
            showHideTooltip(fechaEmisionTooltip, "La fecha no puede ser mayor que la fecha actual.");
            fechaEmisionIsValid = false;
            return;
        } 
    } else {
        fechaEmisionTooltip.classList.remove('green');
        fechaEmisionTooltip.classList.add('red');
        showHideTooltip(fechaEmisionTooltip, "Fecha inválida según calendario.");
        fechaEmisionIsValid = false;
        return;
    }

    fechaEmisionIsValid = true;
    fechaEmisionTooltip.classList.remove('red');
    fechaEmisionTooltip.classList.add('green');
    showHideTooltip(fechaEmisionTooltip, "Formato de fecha válido");
}

let horaEmisionIsValid = true;

function validateManualTimeInput(timeInput) {
    const wantedCharacters = [':', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    keepWantedCharacters(timeInput, wantedCharacters)
    const characterLimits = {
        ':': 2,
    };

    limitSpecificCharacters(timeInput, characterLimits)

    // Expresión regular para validar el formato hh-mm-ss
    const regex = /^(\d{2}):(\d{2}):(\d{2})$/;
    const value = timeInput.value.trim();  
    
    // Verificar si el valor coincide con el formato
    if (!regex.test(value)) {
        horaEmisionTooltip.classList.remove('green');
        horaEmisionTooltip.classList.add('red');
        showHideTooltip(horaEmisionTooltip, "Formato de hora inválido. Debe ser hh:mm:ss");
        horaEmisionIsValid = false;
        return;
    } 

    // Verificar los límites de horas, minutos y segundos
    // Extraer horas, minutos y segundos
    const [_, hours, minutes, seconds] = value.match(regex);

    // Convertir a números
    const hour = parseInt(hours, 10);
    const minute = parseInt(minutes, 10);
    const second = parseInt(seconds, 10);

    horaEmisionTooltip.classList.remove('green');
    horaEmisionTooltip.classList.add('red');

    // Verificar límites de horas, minutos y segundos
    if (hour < 0 || hour > 23) {
        showHideTooltip(horaEmisionTooltip, "La horas deben estar entre 00 y 23.");
        horaEmisionIsValid = false;
        return;
    }
    if (minute < 0 || minute > 59) {
        showHideTooltip(horaEmisionTooltip, "Los minutos deben estar entre 00 y 59.");
        horaEmisionIsValid = false;
        return;
    }
    if (second < 0 || second > 59) {
        showHideTooltip(horaEmisionTooltip, "Los segundos deben estar entre 00 y 59.");
        horaEmisionIsValid = false;
        return;
    }

    horaEmisionIsValid = true;

    horaEmisionTooltip.classList.remove('red');
    horaEmisionTooltip.classList.add('green');
    showHideTooltip(horaEmisionTooltip,"Formato de hora válido");
}

function validatePositiveFloat(input) {
    // Obtener el valor del input
    let value = input.value;
    
    // Eliminar todos los caracteres que no sean dígitos o punto decimal
    let newValue = value.replace(/[^\d.]/g, '');
    
    // Asegurar que solo haya un punto decimal
    let parts = newValue.split('.');
    if (parts.length > 2) {
        parts = [parts[0], parts.slice(1).join('')];
    }
    newValue = parts.join('.');
    
    // Limitar a dos decimales
    if (parts.length > 1) {
        parts[1] = parts[1].slice(0, 2);
        newValue = parts.join('.');
    }
    
    // Remover ceros iniciales innecesarios
    newValue = newValue.replace(/^0+(?=\d)/, '');
    
    // Si el valor es vacío o solo un punto, establecer a cero
    if (newValue === '' || newValue === '.') {
        newValue = '0';
    }
    
    // Actualizar el campo de entrada con el valor filtrado
    if (newValue !== value) {
        input.value = newValue;
        
        // Mover el cursor al final del input
        input.setSelectionRange(newValue.length, newValue.length);
    }
}

function updateDNIRUCMaxLength(numDocumentoClienteInput) {
    // Obtén el tipo de documento seleccionado
    const tipoDocumentoInput = document.getElementById('tipoCodigoClienteInput');
    const numDocumentoInput = numDocumentoClienteInput;

    // Verifica si los elementos se encontraron correctamente
    if (!tipoDocumentoInput || !numDocumentoInput) {
        console.error('No se encontraron los elementos con los IDs proporcionados.');
        return;
    }

    // Define los límites de longitud para cada tipo de documento
    const limites = {
        DNI: 8,
        RUC: 11
    };

    // Obtiene la longitud máxima correspondiente al tipo de documento seleccionado
    const tipoDocumento = tipoDocumentoInput.value.trim(); // Usa trim() para eliminar espacios en blanco
    const maxLength = limites[tipoDocumento] || null;

    // Establece el atributo maxlength o elimina el valor del input
    if (maxLength !== null) {
        numDocumentoInput.maxLength = maxLength;
    } else {
        numDocumentoInput.removeAttribute('maxlength'); // Elimina el atributo maxlength si no es válido
        numDocumentoInput.value = ''; // Vacía el valor del input
        showHideTooltip(codigoClienteTooltip, "Seleccione tipo de documento primero");
    }
}

function clearNumDocumento() {
    codigoClienteInput.value = "";
}

document.addEventListener('DOMContentLoaded', function() {
    function updatePuntosGanados() {
        // Copia el valor de "Monto total" al campo de "Puntos generados"
        puntosGanadosInput.value = Math.round(parseFloat(montoTotalInput.value));
    }
   
    // Agrega un listener para el evento "input" en "Monto total"
    montoTotalInput.addEventListener('input', updatePuntosGanados);
});

let dateValue = ''; // Variable para almacenar la fecha
let timeValue = ''; // Variable para almacenar la hora

function updateDateInput(input) {
    dateValue = input.value; // Actualiza la variable de fecha
    updateHiddenDateTimeInput(); // Actualiza el campo oculto
}

function updateTimeInput(input) {
    timeValue = input.value; // Actualiza la variable de hora
    updateHiddenDateTimeInput(); // Actualiza el campo oculto
}

function updateHiddenDateTimeInput() {
    // Combinar la fecha y la hora en el formato deseado
    const combinedDateTime = `${dateValue} ${timeValue}`;
    fechaHoraEmisionInput.value = combinedDateTime;
}

function validarCamposVaciosFormulario() {
    let allFilled = true;
    formInputsArrayAgregarVenta.forEach(input => {
        if (!input.value.trim()) {
            allFilled = false;

        }
    });

    return allFilled;
}

let mensajeCombinado = "";

function validateDNIRUCLength() {
    if (tipoCodigoClienteInput.value === "DNI") {
        if (codigoClienteInput.value.length !== 8) {
            mensajeCombinado += "El número de DNI debe de tener 8 dígitos. ";
            return false;
        }
    } else {
        if (codigoClienteInput.value.length !== 11) {
            mensajeCombinado += "El número de RUC debe de tener 11 dígitos. ";
            return false;
        }
    }

    return true;
}

function validarCamposCorrectosFormulario() {
    mensajeCombinado = "";
    var returnError = false;

    // Validando documento de cliente
    if (!validateDNIRUCLength()) {
        returnError = true;
    }

    // Validando número de comprobante
    if (!numComprobanteIsValid) {
        mensajeCombinado += "Número de comprobante inválido. ";
        returnError = true;
    }

    // Validando fecha de emisión
    if (!fechaEmisionIsValid) {
        mensajeCombinado += "Fecha de emisión inválida. ";
        returnError = true;
    }

    // Validando hora de emisión
    if (!horaEmisionIsValid) {
        mensajeCombinado += "Hora de emisión inválida. ";
        returnError = true;
    }

    // Validando monto total
    if (montoTotalInput.value == 0) {
        mensajeCombinado += "Monto total no puede ser 0.";
        returnError = true;
    }

    // Validando máximo de 90 días transcurridos
    if (!validarMaximoDiasTranscurridosHastaHoyFechaHora(fechaHoraEmisionInput.value)) {
        mensajeCombinado += `Los días transcurridos de la venta hasta hoy (${Math.floor(diasTranscurridosVentaHastaHoy)}) superan el máximo de \
                            días para el registro de la venta intermediada (${maxdaysCanjeMAIN}). `;
        returnError = true;
    }
    
    if (returnError) {
        return false;
    }

    multiMessageError2.classList.remove("shown");
    return true;
}

function getStringFormatCurrentDate() {
    const dateNow = new Date();
    const fechaHoraActual = `${dateNow.getFullYear()}-${(dateNow.getMonth() + 1).toString().padStart(2, '0')}-${dateNow.getDate().toString().padStart(2, '0')} ${dateNow.getHours().toString().padStart(2, '0')}:${dateNow.getMinutes().toString().padStart(2, '0')}:${dateNow.getSeconds().toString().padStart(2, '0')}`;
    return fechaHoraActual
}



// Función mejorada para validar días transcurridos
function validarMaximoDiasTranscurridosHastaHoyFechaHora(fechaHora) {
    const fechaHoraActual = getStringFormatCurrentDate();
    diasTranscurridosVentaHastaHoy = getDiasTranscurridosFechaHora(fechaHora, fechaHoraActual);
    
    return diasTranscurridosVentaHastaHoy <= maxdaysCanjeMAIN;
}

function removeZerosIDVentaIntermediada(idVentaIntermediada) {
    // Dividir el identificador en la parte antes y después del guion
    const [prefix, suffix] = idVentaIntermediada.split('-');

    // Quitar los ceros iniciales de la parte después del "-"
    const trimmedSuffix = suffix.replace(/^0+/, '');

    // Recombinar las partes
    return `${prefix}-${trimmedSuffix}`;
}

async function guardarModalAgregarVenta(idModal, idForm) { 
    try {
        const idVentaIntermediada = idVentaIntermediadaInput.value.trim();
        const url = `${baseUrlMAIN}/verificar-venta`;
        
        // Validar el formulario en el cliente
        if (!validarCamposVaciosFormulario()) {
            multiMessageError2.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
            multiMessageError2.classList.add("shown");
            return;
        }

        if (!validarCamposCorrectosFormulario()) {
            multiMessageError2.textContent = mensajeCombinado;
            multiMessageError2.classList.add("shown");
            return;
        }

        // Validar existencia de venta intermediada con fetch
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfTokenMAIN
            },
            body: JSON.stringify({ idVentaIntermediada })
        });

        if (!response.ok) {
            const errorDetails = await response.text();
            throw new Error(`Error en la solicitud: ${response.status} - ${response.statusText}. Detalles: ${errorDetails}`);
        }

        const data = await response.json();

        if (data.exists) {
            multiMessageError2.textContent = `El número de comprobante ${idVentaIntermediada} ya ha sido registrado anteriormente.`;
            multiMessageError2.classList.add('shown');
            return; 
        }

        // Si todas las validaciones son correctas, enviar el formulario
        if (validateDate()) {
            multiMessageError2.classList.remove('shown');
            guardarModal(idModal, idForm);
        }
    } catch (error) {
        // Manejo de errores
        console.error(error);
        multiMessageError2.textContent = 'Ocurrió un error al verificar la existencia previa del comprobante. Por favor, inténtelo de nuevo.';
        multiMessageError2.classList.add('shown');
    }
}


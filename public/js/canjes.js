let tecnicoCanjesInput = document.getElementById('tecnicoCanjesInput');
let idTecnicoOptions = 'tecnicoCanjesOptions';
let tecnicoCanjesTooltip = document.getElementById('idTecnicoCanjesTooltip');
let messageErrorTecnicoCanjesInput = document.getElementById('messageErrorTecnicoCanjes')
let recompensaCanjesTooltip = document.getElementById('idRecompensaCanjesTooltip');
let numComprobanteCanjesTooltip = document.getElementById('idNumComprobanteCanjesTooltip');
let resumenContainer = document.getElementById('idResumenContainer');
let numComprobanteCanjesInput = document.getElementById('comprobanteCanjesInput');
let puntosActualesCanjesInput = document.getElementById('puntosActualesCanjesInput');
let comprobantesFetch = [];
let puntosGeneradosCanjesInput = document.getElementById('puntosGeneradosCanjesInput');
let puntosRestantesCanjesInput = document.getElementById('puntosRestantesCanjesInput');
let clienteCanjesTextarea = document.getElementById('clienteCanjesTextarea');
let fechaEmisionCanjesInput = document.getElementById('fechaEmisionCanjesInput');
let fechaCargadaCanjesInput = document.getElementById('fechaCargadaCanjesInput');

function getFormattedDate() {
    let today = new Date();
    let day = String(today.getDate()).padStart(2, '0'); // Obtener el día y añadir un 0 si es necesario
    let month = String(today.getMonth() + 1).padStart(2, '0'); // Obtener el mes y añadir un 0 si es necesario
    let year = today.getFullYear(); // Obtener el año

    return `${year}-${month}-${day}`; // Formato YYYY-MM-DD (requerido para inputs tipo date)
}

let date = getFormattedDate();
let tecnicoFilledCorrectlySearchField = false;
let recompensaFilledCorrectlySearchField = false;

document.addEventListener("DOMContentLoaded", function() {
    let fechaCanjeInput = document.getElementById('idFechaCanjeInput');
    fechaCanjeInput.value = date;  // Asigna la fecha en formato YYYY-MM-DD
});

function consoleLogJSONItems(items) {
    console.log(JSON.stringify(items, null, 2));
}

function selectOptionNumComprobanteCanjes(value, idInput, idOptions) {
    if (tecnicoCanjesInput.value && tecnicoFilledCorrectlySearchField) {
        resumenContainer.classList.add('shown');
        selectOption(value, idInput, idOptions); 

        // Verificamos si `comprobantesFetch` contiene datos
        if (comprobantesFetch && comprobantesFetch.length > 0) {
            // Buscamos el comprobante que tenga el ID que coincide con `value`
            const comprobanteSeleccionado = comprobantesFetch.find(comprobante => comprobante.idVentaIntermediada === value);

            if (comprobanteSeleccionado) {
                // Asignamos los valores del comprobante seleccionado a los inputs
                puntosGeneradosCanjesInput.value = comprobanteSeleccionado.puntosGanados_VentaIntermediada || '';
                puntosRestantesCanjesInput.value = comprobanteSeleccionado.montoTotal_VentaIntermediada || '';
                clienteCanjesTextarea.value = 
                    (comprobanteSeleccionado.nombreCliente_VentaIntermediada +  "\n" + 
                    comprobanteSeleccionado.tipoCodigoCliente_VentaIntermediada +  ": " + 
                    comprobanteSeleccionado.codigoCliente_VentaIntermediada) || '';
                fechaEmisionCanjesInput.value = comprobanteSeleccionado.fechaHoraEmision_VentaIntermediada ? comprobanteSeleccionado.fechaHoraEmision_VentaIntermediada.split(' ')[0] : ''; // Solo la fecha
                fechaCargadaCanjesInput.value = comprobanteSeleccionado.fechaHoraCargada_VentaIntermediada ? comprobanteSeleccionado.fechaHoraCargada_VentaIntermediada.split(' ')[0] : ''; // Solo la fecha
                
                fechaEmisionCanjesInput.classList.remove("noEditable");
                fechaCargadaCanjesInput.classList.remove("noEditable");

                console.log("Comprobante seleccionado:", comprobanteSeleccionado);
            } else {
                console.error("No se encontró el comprobante con el ID:", value);
            }
        } else {
            console.error("No se encontraron comprobantes.");
        }
        return;
    }

    fechaEmisionCanjesInput.classList.add("noEditable");
    fechaCargadaCanjesInput.classList.add("noEditable");
    resumenContainer.classList.remove('shown');
}

function toggleNumComprobanteCanjesOptions(idInput, idOptions) {
    // Verificamos que el campo del técnico tenga un valor y separemos el DNI y el nombre
    const tecnicoValue = tecnicoCanjesInput.value;

    // Verificamos que se haya encontrado un técnico y que coincida con las opciones disponibles
    const allItems = getAllLiText(idTecnicoOptions); 
    const itemEncontrado = allItems.includes(tecnicoValue);

    console.log(allItems);
    console.log(tecnicoValue);
    console.log(itemEncontrado);

    if (itemEncontrado) {
        toggleOptions(idInput, idOptions); // Mostrar u ocultar las opciones
    } else {
        showHideTooltip(tecnicoCanjesTooltip, "Seleccione un Técnico primero");
    }
}


function validateOptionTecnicoCanjes(input, idOptions, idMessageError, tecnicosDB) {
    const value = input.value;
    const messageError = document.getElementById(idMessageError);

    // Obtener todos los valores del item (la función está en dashboardScrip.js)
    const allItems = getAllLiText(idOptions);

    // Comparar el valor ingresado con la lista de items 
    const itemEncontrado = allItems.includes(value);
    const [idTecnico, nombreTecnico] = value.split(' - ');

    if (value) {
        if (!itemEncontrado)  {
            messageError.textContent = "No se encontró el técnico buscado";
            messageError.classList.add('shown'); 
            puntosActualesCanjesInput.value = "";   
            numComprobanteCanjesInput.value = "";
            tecnicoFilledCorrectlySearchField = false;
        } else {
            filterNumComprobantesInputWithTecnicoFetch(idTecnico);
            const puntosTecnicoIngresado = returnPuntosActualesDBWithRequestedTecnicoID(idTecnico, tecnicosDB);
            puntosActualesCanjesInput.value = puntosTecnicoIngresado;
            tecnicoFilledCorrectlySearchField = true;
        }
    } else {
        messageError.classList.remove('shown'); 
    }
}

function selectOptionTecnicoCanjes(value, idInput, idOptions, puntosActuales, idTecnico) {
    selectOption(value, idInput, idOptions);
    puntosActualesCanjesInput.value = puntosActuales;
    tecnicoFilledCorrectlySearchField = true;
    filterNumComprobantesInputWithTecnicoFetch(idTecnico);
}

function selectOptionRecompensaCanjes(value, idInput, idOptions) {
    if(!numComprobanteCanjesInput.value) {
        showHideTooltip(numComprobanteCanjesTooltip, "Seleccione un Número de comprobante primero");
        return;
    }

    selectOption(value, idInput, idOptions);
    recompensaCanjesTooltip.classList.remove('red');
    recompensaCanjesTooltip.classList.add('green');
    showHideTooltip(recompensaCanjesTooltip, "Recompensa encontrada");
}

function validateOptionRecompensaCanjes(input, idOptions, idMessageError, recompensasDB) {
    const value = input.value;
    //const messageError = document.getElementById(idMessageError);

    // Obtener todos los valores del item (la función está en dashboardScrip.js)
    const allItems = getAllLiText(idOptions);

    // Comparar el valor ingresado con la lista de items 
    const itemEncontrado = allItems.includes(value);
   
    // Valor no encontrado o 
    if (value && !itemEncontrado)  {
        //messageError.classList.add('shown'); 
        recompensaFilledCorrectlySearchField = false;
        recompensaCanjesTooltip.classList.remove('green');
        recompensaCanjesTooltip.classList.add('red');
        showHideTooltip(recompensaCanjesTooltip, "No se encontró la recompensa buscada");
    } else if (value && itemEncontrado) {
        //messageError.classList.remove('shown');
        recompensaFilledCorrectlySearchField = true;
        recompensaCanjesTooltip.classList.remove('red');
        recompensaCanjesTooltip.classList.add('green');
        showHideTooltip(recompensaCanjesTooltip, "Recompensa encontrada");
    }
}

function validateNumComprobanteInputNoEmpty(recompensaCanjesInput) {
    if(!numComprobanteCanjesInput.value) {
        recompensaCanjesInput.value = '';
        showHideTooltip(numComprobanteCanjesTooltip, "Seleccione tipo de documento primero");
    } 
}

function hideResumeContainer() {
    console.log("Limpiando input Número de comprobante");
    resumenContainer.classList.remove('shown');
    puntosGeneradosCanjesInput.value = "";
    puntosRestantesCanjesInput.value = "";
    clienteCanjesTextarea.value = "";
    fechaEmisionCanjesInput.value = "";
    fechaCargadaCanjesInput.value = "";
}

function returnPuntosActualesDBWithRequestedTecnicoID(idTecnico, tecnicosDB) {
    for (const key in tecnicosDB) {
        if (tecnicosDB[key]["idTecnico"] === idTecnico) {
            return tecnicosDB[key]["totalPuntosActuales_Tecnico"];
        }
    }
    return null; 
}

async function filterNumComprobantesInputWithTecnicoFetch(idTecnico) {
    const url = `http://localhost/FidelizacionTecnicos/public/dashboard-canjes/tecnico/${idTecnico}`;

    try {
        const response = await fetch(url);
        /*console.log("Fetching URL:", url);
        console.log('Response Status:', response.status);
        console.log('Response Headers:', response.headers.get('Content-Type'));*/

        if (!response.ok) {
            throw new Error(await response.text());
        }

        comprobantesFetch = await response.json();
    
        if (comprobantesFetch.length == 0) {
            messageErrorTecnicoCanjesInput.textContent = 'El técnico encontrado no tiene ventas intermediadas "EN ESPERA"'
            messageErrorTecnicoCanjesInput.classList.add('shown');
        } else {
            messageErrorTecnicoCanjesInput.classList.remove('shown');
        }

        const comprobanteOptions = document.getElementById('comprobanteOptions');
        comprobanteOptions.innerHTML = '';

        comprobantesFetch.forEach(comprobante => {
            const li = document.createElement('li');
            li.textContent = `${comprobante.idVentaIntermediada}`;
            li.onclick = function() {
                selectOptionNumComprobanteCanjes(`${comprobante.idVentaIntermediada}`, 'comprobanteCanjesInput', 'comprobanteOptions');
            };
            comprobanteOptions.appendChild(li);
        });
    } catch (error) {
        console.error('Error al obtener los comprobantes:', error.message);
        // Aquí puedes añadir código para mostrar el error al usuario
    }
}
    

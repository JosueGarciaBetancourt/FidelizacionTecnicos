let tecnicoCanjesInput = document.getElementById('tecnicoCanjesInput');
let idTecnicoOptions = 'tecnicoCanjesOptions';
let tecnicoCanjesTooltip = document.getElementById('idTecnicoCanjesTooltip');
let messageErrorTecnicoCanjesInput = document.getElementById('messageErrorTecnicoCanjes')
let recompensaCanjesTooltip = document.getElementById('idRecompensaCanjesTooltip');
let cantidadCanjesTooltip = document.getElementById('idCantidadCanjesTooltip');
let numComprobanteCanjesTooltip = document.getElementById('idNumComprobanteCanjesTooltip');
let resumenContainer = document.getElementById('idResumenContainer');
let numComprobanteCanjesInput = document.getElementById('comprobanteCanjesInput');
let puntosActualesCanjesInput = document.getElementById('puntosActualesCanjesInput');
let comprobantesFetch = [];
let puntosGeneradosCanjesInput = document.getElementById('puntosGeneradosCanjesInput');
//let puntosRestantesCanjesInput = document.getElementById('puntosRestantesCanjesInput');
let clienteCanjesTextarea = document.getElementById('clienteCanjesTextarea');
let fechaEmisionCanjesInput = document.getElementById('fechaEmisionCanjesInput');
let fechaCargadaCanjesInput = document.getElementById('fechaCargadaCanjesInput');
let diasTranscurridosInput = document.getElementById('diasTranscurridosInput');
let cantidadRecompensaCanjesInput = document.getElementById('cantidadRecompensaCanjesInput');
let agregarRecompensaTablaBtn = document.getElementById('idAgregarRecompensaTablaBtn');
let recompensasCanjesInput = document.getElementById('recompensasCanjesInput');
let tblCanjesMessageBelow = document.getElementById('tblCanjesMessageBelow');
let tableCanjesBody = document.querySelector('#tblCanjes tbody');
let tableCanjesFooter = document.querySelector('#tblCanjes tfoot');
let celdaTotalPuntos = document.getElementById('celdaTotalPuntos');
let numFilaSeleccionada = null;
let lastNumFilaSeleccionada = null;
let listaFilasSeleccionadas = [];
let lastSelectedRow = null;
let puntosComprobanteResumen = document.getElementById('labelPuntosComprobante');
let puntosCanjeadosResumen = document.getElementById('labelPuntosCanjeados');
let puntosRestantesResumen = document.getElementById('labelPuntosRestantes');
let sumaPuntosTotalesTablaRecompensasCanjes = 0;
let allCeldasSubtotalPuntos;

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
    verificarFilasTablaCanjes(); // Llamar cuando se carga la página
});

function consoleLogJSONItems(items) {
    console.log(JSON.stringify(items, null, 2));
}

function getDiasTranscurridos(fechaEmision, fechaCargada) {
    // Asignar las fechas de los inputs
    var emi = new Date(fechaEmision); // Convierte la fecha de emisión a un objeto Date
    var carg = new Date(fechaCargada); // Convierte la fecha cargada a un objeto Date

    // Calcula la diferencia en milisegundos
    var diferenciaMilisegundos = Math.abs(carg - emi);

    // Convertir milisegundos a días
    var diasTranscurridos = diferenciaMilisegundos / (1000 * 60 * 60 * 24);

    return Math.floor(diasTranscurridos); // Redondea hacia abajo al número entero más cercano
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
                //puntosRestantesCanjesInput.value = comprobanteSeleccionado.montoTotal_VentaIntermediada || '';
                clienteCanjesTextarea.value = 
                    (comprobanteSeleccionado.nombreCliente_VentaIntermediada +  "\n" + 
                    comprobanteSeleccionado.tipoCodigoCliente_VentaIntermediada +  ": " + 
                    comprobanteSeleccionado.codigoCliente_VentaIntermediada) || '';
                fechaEmisionCanjesInput.value = comprobanteSeleccionado.fechaHoraEmision_VentaIntermediada ? comprobanteSeleccionado.fechaHoraEmision_VentaIntermediada.split(' ')[0] : ''; // Solo la fecha
                fechaCargadaCanjesInput.value = comprobanteSeleccionado.fechaHoraCargada_VentaIntermediada ? comprobanteSeleccionado.fechaHoraCargada_VentaIntermediada.split(' ')[0] : ''; // Solo la fecha

                diasTranscurridosInput.value = getDiasTranscurridos(fechaEmisionCanjesInput.value , fechaCargadaCanjesInput.value);

                fechaEmisionCanjesInput.classList.remove("noEditable");
                fechaCargadaCanjesInput.classList.remove("noEditable");

                // Llenar los campos del cuadro Resumen
                puntosComprobanteResumen.textContent = puntosGeneradosCanjesInput.value;
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

    /*console.log(allItems);
    console.log(tecnicoValue);
    console.log(itemEncontrado);*/

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
    if (!tecnicoCanjesInput.value) {
        recompensaFilledCorrectlySearchField = false;
        showHideTooltip(tecnicoCanjesTooltip, "Seleccione un Técnico primero");
        return;
    }

    if(!numComprobanteCanjesInput.value) {
        recompensaFilledCorrectlySearchField = false;
        showHideTooltip(numComprobanteCanjesTooltip, "Seleccione un Número de comprobante primero");
        return;
    }

    if(!numComprobanteCanjesInput.value) {
        recompensaFilledCorrectlySearchField = false;
        showHideTooltip(numComprobanteCanjesTooltip, "Seleccione un Número de comprobante primero");
        return;
    }

    selectOption(value, idInput, idOptions);
    recompensaCanjesTooltip.classList.remove('red');
    recompensaCanjesTooltip.classList.add('green');
    recompensaFilledCorrectlySearchField = true;
    showHideTooltip(recompensaCanjesTooltip, "Recompensa encontrada");
}

function validateOptionRecompensaCanjes(input, idOptions, idMessageError, recompensasDB) {
    if (!tecnicoCanjesInput.value) {
        input.value = "";
        recompensaFilledCorrectlySearchField = false;
        showHideTooltip(tecnicoCanjesTooltip, "Seleccione un Técnico primero");
        return;
    }

    const value = input.value;
    //const messageError = document.getElementById(idMessageError);

    // Obtener todos los valores del item (la función está en dashboardScrip.js)
    const allItems = getAllLiText(idOptions);

    // Comparar el valor ingresado con la lista de items 
    const itemEncontrado = allItems.includes(value);

    // Valor no encontrado 
    if (!value) {
        recompensaFilledCorrectlySearchField = false;
    } else if (value && !itemEncontrado)  {
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
        showHideTooltip(numComprobanteCanjesTooltip, "Seleccione un Número de comprobante primero");
    } 
}

function cleanAllNumeroComprobante() {
    // Limpiar todos los campos correspondientes al número de comprobante 
    puntosGeneradosCanjesInput.value = "";
    clienteCanjesTextarea.value = "";
    fechaEmisionCanjesInput.value = "";
    fechaCargadaCanjesInput.value = "";
    diasTranscurridosInput.value = "";
    fechaEmisionCanjesInput.classList.add("noEditable");
    fechaCargadaCanjesInput.classList.add("noEditable");

    // Ocultar cuadro resumen
    resumenContainer.classList.remove('shown');
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
    //const url = `http://localhost/FidelizacionTecnicos/public/dashboard-canjes/tecnico/${idTecnico}`;
    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`; // Esto adaptará la URL al dominio actual
    const url = `${baseUrl}/dashboard-canjes/tecnico/${idTecnico}`;
    console.warn("fetch", url);

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

let incrementInterval; // Para el incremento
let decrementInterval; // Para el decremento

function countUpCantidadRecompensa() {
    if (cantidadRecompensaCanjesInput.value == null || cantidadRecompensaCanjesInput.value == "" ) {
        cantidadRecompensaCanjesInput.value = 1;
    } else if (cantidadRecompensaCanjesInput.value < 100) {
        cantidadRecompensaCanjesInput.value = parseInt(cantidadRecompensaCanjesInput.value) + 1;
    }

    // Esto asegura que se dispare el observer
    cantidadRecompensaCanjesInput.setAttribute('value', (parseInt(cantidadRecompensaCanjesInput.value) || 0) + 1); 
}

function countDownCantidadRecompensa() {
    if (cantidadRecompensaCanjesInput.value != null && cantidadRecompensaCanjesInput.value != "" && cantidadRecompensaCanjesInput.value > 0) {
        // Convertir texto a entero y disminuir en 1 el valor de la actual cantidad
        cantidadRecompensaCanjesInput.value = parseInt(cantidadRecompensaCanjesInput.value) - 1;
        cantidadRecompensaCanjesInput.setAttribute('value', parseInt(cantidadRecompensaCanjesInput.value) - 1); 
    }  
}

// Manejo del evento de mouse para incrementar
document.getElementById('incrementButton').addEventListener('mousedown', function() {
    countUpCantidadRecompensa(); // Llamar una vez para iniciar
    incrementInterval = setInterval(countUpCantidadRecompensa, 250); // Ajusta el intervalo según sea necesario
});

// Manejo del evento de mouse para decrementar
document.getElementById('decrementButton').addEventListener('mousedown', function() {
    countDownCantidadRecompensa(); // Llamar una vez para iniciar
    decrementInterval = setInterval(countDownCantidadRecompensa, 250); // Ajusta el intervalo según sea necesario
});

// Detener el incremento o decremento al soltar el botón
document.addEventListener('mouseup', function() {
    clearInterval(incrementInterval);
    clearInterval(decrementInterval);
});

// Detener el intervalo si el ratón sale del botón
/*document.getElementById('incrementButton').addEventListener('mouseleave', function() {
    clearInterval(incrementInterval);
});

document.getElementById('decrementButton').addEventListener('mouseleave', function() {
    clearInterval(decrementInterval);
});*/

document.addEventListener('DOMContentLoaded', function() {
    // Recuperar valores guardados y establecerlos en los inputs
    document.querySelectorAll('.persist-input').forEach(function(input) {
        //console.log(input.id);
        var savedValue = localStorage.getItem(input.id);
        if (savedValue !== null) {
            input.value = savedValue;
            //console.log(input.id + ": " + input.value);
        }

        // Configurar el observador para que observe cambios en los atributos
        const observer = new MutationObserver(handleInputChange);

        // Opciones de configuración del observador
        const config = { attributes: true, attributeFilter: ['value'] };

        // Iniciar la observación del input
        observer.observe(input, config);

        // Añadir event listener para guardar el valor cuando cambie
        input.addEventListener('input', function() {
            // Guardar el valor actual en localStorage
            console.log("Guardando en local storage: ", input.id, " con valor: ", input.value)
            localStorage.setItem(input.id, input.value);
        });
    });
});

// Función para limpiar todos los valores guardados
function clearAllPersistedInputs() {
    document.querySelectorAll('.persist-input').forEach(function(input) {
        localStorage.removeItem(input.id);
        input.value = '';
    });
}

// Función para limpiar un input específico
function clearPersistedInput(inputId) {
    localStorage.removeItem(inputId);
    var input = document.getElementById(inputId);   
    if (input) {
        input.value = '';
    }
}

// Función que se ejecutará cuando el valor del input cambie
function handleInputChange(mutationsList, observer) {
    mutationsList.forEach(function(mutation) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
            console.log('El valor del input ha sido observado:', mutation.target.id, mutation.target.value);
            // Aquí puedes manejar el cambio del valor
            localStorage.setItem(mutation.target.id, mutation.target.value);
        }
    });
}

// Lógica de la tabla de recompensas agregadas en la sección CANJES
function agregarFilaRecompensa() {
    try {
        if (!recompensasCanjesInput.value) {
            recompensaCanjesTooltip.classList.remove("green");
            recompensaCanjesTooltip.classList.add("red");
            showHideTooltip(recompensaCanjesTooltip, "Seleccione una recompensa primero");
            throw new Error('Ingrese una recompensa válida');
        }

        // Verifica si el campo de cantidad tiene un valor
        if (!cantidadRecompensaCanjesInput.value || isNaN(cantidadRecompensaCanjesInput.value) || cantidadRecompensaCanjesInput.value <= 0) {
            cantidadCanjesTooltip.classList.remove("green");
            cantidadCanjesTooltip.classList.add("red");
            showHideTooltip(cantidadCanjesTooltip, "Ingrese una cantidad válida");
            throw new Error('Ingrese una cantidad válida');
        }

        const cantidad = parseInt(cantidadRecompensaCanjesInput.value, 10);

        const partesRecompensaValue = recompensasCanjesInput.value.split(" | ");

        // Verifica que el formato de la recompensa sea correcto (se esperan 4 partes)
        if (partesRecompensaValue.length !== 4) {
            recompensaCanjesTooltip.classList.remove("green");
            recompensaCanjesTooltip.classList.add("red");
            showHideTooltip(recompensaCanjesTooltip, 'El formato de la recompensa es incorrecto.  Se requieren 4 partes separadas por " | "');
            throw new Error('El formato de la recompensa es incorrecto. Se requieren 4 partes separadas por " | "');
        }

        // Asignación de variables a cada parte
        const [codigo, categoria, descripcion, costoRaw] = partesRecompensaValue;
        const costo = parseInt(costoRaw.replace(" puntos", "").trim(), 10);

        // Validar que el costo sea un número válido
        if (isNaN(costo) || costo <= 0) {
            recompensaCanjesTooltip.classList.remove("green");
            recompensaCanjesTooltip.classList.add("red");
            showHideTooltip(recompensaCanjesTooltip, 'El costo de la recompensa es menor igual a 0, no es válido');
            throw new Error('El costo de la recompensa es menor igual a 0, no es válido');
        }

        // Calcular puntos totales de la recompensa nueva
        const puntosTotalesRecompensaNueva = cantidad * costo;

        // Función para validar que la suma de puntos no exceda el límite
        function validatePuntosExceeds(sumaPuntosTotalesFilas, puntosGenerados) {
            if (sumaPuntosTotalesFilas > puntosGenerados) {
                recompensaCanjesTooltip.classList.remove("green");
                recompensaCanjesTooltip.classList.add("red");
                const message = `El nuevo total de puntos (${sumaPuntosTotalesFilas}) excede a los puntos generados por el comprobante (${puntosGenerados})`;
                showHideTooltip(recompensaCanjesTooltip, message);
                throw new Error(message);
            }
        }

        // Calcular la suma de puntos totales 
        var sumaPuntosTotalesFilas = 0;
        const puntosGenerados = puntosGeneradosCanjesInput.value;

        // Validar recompensa duplicada en tabla
        const puntosRecompensaAntigua= isCodigoDuplicated(codigo);
        
        if (puntosRecompensaAntigua) {
            // Si la recompensa ya fue agregada entonces calcular la suma de puntos totales con la nueva recompensa
            sumaPuntosTotalesFilas = getSumaTotalPuntosTblRecompensasCanjes() - puntosRecompensaAntigua + puntosTotalesRecompensaNueva;

            // Validar que la suma no exceda a la cantidad de puntos generados del comprobante
            validatePuntosExceeds(sumaPuntosTotalesFilas, puntosGenerados);

            // Actualizar recompensa duplicada
            updateRow(codigo, cantidad, puntosTotalesRecompensaNueva);
            
            // Actualizar variable global
            sumaPuntosTotalesTablaRecompensasCanjes = sumaPuntosTotalesFilas;

            // Actualizar celda de puntos totales en el footer de la tabla
            verificarFilasTablaCanjes();
            return;
        } 

        // Si la recompensa aún no fue agregada entonces calcular la suma de todo incluyendo la recompensa nueva
        sumaPuntosTotalesFilas = getSumaTotalPuntosTblRecompensasCanjes() + puntosTotalesRecompensaNueva;

        // Validar que la suma no exceda a la cantidad de puntos generados del comprobante
        validatePuntosExceeds(sumaPuntosTotalesFilas, puntosGenerados);

        // Agregar la nueva recompensa a la tabla 
        addRowTableCanjes(codigo, categoria, descripcion, costo, cantidad, puntosTotalesRecompensaNueva);
        
        // Actualizar variable global
        sumaPuntosTotalesTablaRecompensasCanjes = sumaPuntosTotalesFilas;

        // Actualizar celda de puntos totales en el footer de la tabla
        verificarFilasTablaCanjes();
    } catch (error) {
        console.error('Error al agregar la recompensa:', error.message);
    }
}

function verificarFilasTablaCanjes() {
    // No hay filas en la tabla
    if (tableCanjesBody.rows.length === 0) {
        tblCanjesMessageBelow.classList.remove('hidden');  
        tableCanjesFooter.classList.add("hidden");
        //console.error("No hay filas en el tbody de la tabla de recompensas (Canjes section)");
        return false;
    } 

    // Sí existen filas
    tblCanjesMessageBelow.classList.add('hidden'); 
    tableCanjesFooter.classList.remove("hidden");

    // Actualizar suma total en el tfoot
    celdaTotalPuntos.textContent = sumaPuntosTotalesTablaRecompensasCanjes;

    // Actualizar puntos a canjear en el cuadro resumen
    updateResumenBoard();
    return true;
}

function getCeldasSubtotalPuntos() {
    const celdasSubtotalPuntos = document.querySelectorAll('.celda-centered.subtotalPuntos');
    
    // Si no hay celdas de subtotal de puntos
    if (celdasSubtotalPuntos.length === 0) {
        console.error("No se encontraron celdas con la clase 'celda-centered subtotalPuntos'");
        return false;
    }

    // Sí hay celdas de subtotal de puntos
    return celdasSubtotalPuntos;
}

function getSumaTotalPuntosTblRecompensasCanjes() {
    if (!verificarFilasTablaCanjes()) {
        return 0;
    }
    
    const allCeldasSubtotalPuntos = getCeldasSubtotalPuntos();
    if (allCeldasSubtotalPuntos === false) {
        return 0;
    }

    // Calcular la suma de puntos totales de todas las filas
    let suma = 0;
    allCeldasSubtotalPuntos.forEach(celda => {
        const valorNumCelda = parseInt(celda.textContent, 10);  // Asegurar base decimal
        if (!isNaN(valorNumCelda)) {  // Validar que sea un número
            suma += valorNumCelda;
        }
    });

    return suma;
}

function isCodigoDuplicated(codigo) {
    if (verificarFilasTablaCanjes()) {
        const tableBody = document.querySelector('#tblCanjes tbody');

        // Verificar que el cuerpo de la tabla existe
        if (!tableBody) {
            console.error('El cuerpo de la tabla no existe.');
            return false;
        }

        // Recorrer todas las filas del cuerpo de la tabla
        for (let row of tableBody.rows) {
            const cellCodigo = row.cells[1]; // Segunda columna (índice 1)

            // Si el código ya existe (con trim para evitar espacios)
            if (cellCodigo.textContent.trim() === codigo) {
                // Resaltar el color de fondo de la fila
                row.classList.add("duplicated");
                setTimeout(() => {
                    row.classList.remove("duplicated");
                }, 1000); 

                puntosTotalesRecompensaEncontrada = parseInt(row.cells[6].textContent, 10); 
                return puntosTotalesRecompensaEncontrada; // Código duplicado encontrado
            }
        }
    }

    return false; // No se encontró el código duplicado
}

function updateRow(codigo, cantidad, puntosTotales) {
    const tableBody = document.querySelector('#tblCanjes tbody');

    // Recorrer todas las filas del cuerpo de la tabla
    for (let row of tableBody.rows) {
        const cellCodigo = row.cells[1]; // Segunda columna (índice 1)

        // Si el código ya existe (con trim para evitar espacios)
        if (cellCodigo.textContent.trim() === codigo) {
            // Sobreescribir la cantidad y puntos totales en la fila existente
            row.cells[5].textContent = cantidad; // Columna de cantidad (índice 5)
            row.cells[6].textContent = puntosTotales; // Columna de puntos totales (índice 6)
            return true; // Se actualizó la fila
        }
    }
    
    return false; // No se encontró el código para actualizar
}

function addRowTableCanjes(codigo, categoria, descripcion, costo, cantidad, puntosTotales) {
    const tableBody = document.querySelector('#tblCanjes tbody');
    const newRow = document.createElement('tr');

    // Datos que serán agregados en las celdas
    const datosFila = [
        tableBody.rows.length + 1,  // Número de fila
        codigo,
        categoria,
        descripcion,
        costo,
        cantidad,
        puntosTotales
    ];

    // Crear y agregar celdas a la fila
    datosFila.forEach((dato, index) => {
        const cell = document.createElement('td');

        // Comprobar si es el índice de puntosTotales (último índice en este caso)
        if (index === datosFila.length - 1) {
            cell.classList.add('celda-centered', 'subtotalPuntos'); // Agregar ambas clases
        } else {
            cell.classList.add('celda-centered'); // Solo agregar 'celda-centered' para otras celdas
        }

        cell.textContent = dato;
        newRow.appendChild(cell);
    });
    
    tableBody.appendChild(newRow);

    // Detectar click en la fila
    newRow.addEventListener('click', function () {
        toggleRowSelection(newRow);
    });
}

function toggleRowSelection(newRow) {
    const selectedRow = document.querySelector('tr.selectedCanjes');

    // Si la fila seleccionada es la misma que la clicada, deseleccionamos
    if (selectedRow === newRow) {
        newRow.classList.remove('selectedCanjes');
        lastSelectedRow = null; // Reseteamos la fila seleccionada
    } else {
        // Deseleccionamos la fila previamente seleccionada
        if (selectedRow) {
            selectedRow.classList.remove('selectedCanjes');
        }
        // Seleccionamos la nueva fila
        newRow.classList.add('selectedCanjes');
        lastSelectedRow = newRow; // Actualizamos la fila seleccionada
        console.log("Fila seleccionada de verdad: " + lastSelectedRow.rowIndex);
    }
}

function eliminarFilaTabla() {
    if (lastSelectedRow) {
        lastSelectedRow.remove();
        lastSelectedRow = null;

        // Actualizar celda de puntos totales en el footer de la tabla
        sumaPuntosTotalesTablaRecompensasCanjes = getSumaTotalPuntosTblRecompensasCanjes();
        verificarFilasTablaCanjes();
        updateResumenBoard();
    }
}

function updateResumenBoard() {
    if (sumaPuntosTotalesTablaRecompensasCanjes <= puntosGeneradosCanjesInput.value) {
        puntosCanjeadosResumen.textContent = sumaPuntosTotalesTablaRecompensasCanjes;
        puntosRestantesResumen.textContent = puntosGeneradosCanjesInput.value - sumaPuntosTotalesTablaRecompensasCanjes;
    } else {
        puntosCanjeadosResumen.textContent = 0;
        puntosRestantesResumen.textContent = 0;
    }
}

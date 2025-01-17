let ventaTecnicoInputDelete = document.getElementById('ventaTecnicoInputDelete');
let ventaTecnicoOptionsDelete = document.getElementById('ventaOptions');
let ventaIntermediadaInputDelete = document.getElementById('idVentaIntermediada');
let fechaEmisionVentaIntermediadaInputDelete = document.getElementById('fechaEmisionVentaIntermediadaInputDelete');
let horaEmisionVentaIntermediadaInputDelete = document.getElementById('horaEmisionVentaIntermediadaInputDelete');
let montoTotalInputDelete = document.getElementById('montoTotalInputDelete');
let puntosGeneradosInputDelete = document.getElementById('puntosGeneradosInputDelete');
let fechaCargadaInputDelete = document.getElementById('fechaCargadaInputDelete');
let horaCargadaInputDelete = document.getElementById('horaCargadaInputDelete');
let tipoCodigoClienteInputDelete = document.getElementById('tipoCodigoClienteInputDelete');
let codigoClienteInputDelete = document.getElementById('codigoClienteInputDelete');
let nombreClienteInputDelete = document.getElementById('nombreClienteInputDelete');
let eliminarVentaSearchMessageError = document.getElementById('eliminarVentaMessageError');
let eliminarVentaMultiMessageErrorDelete = document.getElementById('eliminarVentaMultiMessageError');

/* function selectOptionEliminarVenta(value, idInput, idOptions, fecha, hora, montoTotal, puntosGenerados, fechaCargada, horaCargada, tipoCodigoCliente, codigoCliente, nombreCliente) {
    //Colocar en el input la opción seleccionada 
    selectOption(value, idInput, idOptions); 

    // Extraer id y nombre del valor
    const [id] = value.split(' | ');
    
    if (id) {
        // Actualizar los campos ocultos
        ventaIntermediadaInputDelete.value = id;
        // Actualizar los demás campos
        fechaEmisionVentaIntermediadaInputDelete.value = fecha;
        horaEmisionVentaIntermediadaInputDelete.value = hora;
        montoTotalInputDelete.value = montoTotal;
        puntosGeneradosInputDelete.value = puntosGenerados;
        fechaCargadaInputDelete.value = fechaCargada;
        horaCargadaInputDelete.value = horaCargada;
        tipoCodigoClienteInputDelete.value = tipoCodigoCliente;
        codigoClienteInputDelete.value = codigoCliente;
        nombreClienteInputDelete.value = nombreCliente;
    } else {
        // Limpiar campos ocultos
        ventaIntermediadaInputDelete.value = "";
        // Limpiar los demás campos
        fechaEmisionVentaIntermediadaInputDelete.value = "";
        horaEmisionVentaIntermediadaInputDelete.value = "";
        montoTotalInputDelete.value = "";
        puntosGeneradosInputDelete.value = "";
        fechaCargadaInputDelete.value = "";
        horaCargadaInputDelete.value = "";
        tipoCodigoClienteInputDelete.value = "";
        codigoClienteInputDelete.value = "";
        nombreClienteInputDelete.value = "";
    }

    eliminarVentaSearchMessageError.classList.remove('shown'); 
} */

/* INICIO Funciones para manejar el input dinámico */
let currentPageEliminarVenta = 1; // Página actual

function selectOptionEliminarVenta(value, venta) {
    // Colocar en el input la opción seleccionada 
    selectOption(value, ventaTecnicoInputDelete.id, ventaTecnicoOptionsDelete.id); 
    
    //consoleLogJSONItems(venta);
    
    if (!venta) {
        // Limpiar los demás campos
        fechaEmisionVentaIntermediadaInputDelete.value = "";
        horaEmisionVentaIntermediadaInputDelete.value = "";
        montoTotalInputDelete.value = "";
        puntosGeneradosInputDelete.value = "";
        fechaCargadaInputDelete.value = "";
        horaCargadaInputDelete.value = "";
        tipoCodigoClienteInputDelete.value = "";
        codigoClienteInputDelete.value = "";
        nombreClienteInputDelete.value = "";

        // Limpiar campos ocultos
        ventaIntermediadaInputDelete.value = "";
        return;
    }
   
    // Llenar los demás campos
    fechaEmisionVentaIntermediadaInputDelete.value = venta.fechaVenta;
    horaEmisionVentaIntermediadaInputDelete.value = venta.horaVenta;
    montoTotalInputDelete.value = venta.montoTotal_VentaIntermediada;
    puntosGeneradosInputDelete.value = venta.puntosGanados_VentaIntermediada;
    fechaCargadaInputDelete.value = venta.fechaCargada;
    horaCargadaInputDelete.value = venta.horaCargada;
    tipoCodigoClienteInputDelete.value = venta.tipoCodigoCliente_VentaIntermediada;
    codigoClienteInputDelete.value = venta.codigoCliente_VentaIntermediada;
    nombreClienteInputDelete.value = venta.nombreCliente_VentaIntermediada;

    // Llenar campos ocultos
    ventaIntermediadaInputDelete.value = venta.idVentaIntermediada;
    eliminarVentaSearchMessageError.classList.remove("shown");
}

async function filterOptionsEliminarVenta(input, idOptions) {
    const filter = input.value.trim().toUpperCase();
    const ul = document.getElementById(idOptions);
    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`;  
    const url = `${baseUrl}/dashboard-ventasIntermediadas/getFilteredVentas`;

    if (!filter) {
        currentPageEliminarVenta = 1;
        ul.innerHTML = "";
        loadPaginatedOptionsEliminarVenta(idOptions);
        return;
    }
    
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
            console.warn(`Error en filterOptionsEliminarVenta: ${errorData.message}`);
            const li = document.createElement('li');
            li.textContent = 'No se encontraron técnicos';
            ul.appendChild(li);
            ul.classList.add('show');
        }
        return;
    }

    const data = await response.json();

    // Mostrar las opciones y agregarlas dinámicamente
    data.data.forEach(venta => {
        const li = document.createElement('li');
        const value = `${venta.idVentaIntermediada} | ${venta.idTecnico} - ${venta.nombreTecnico}`;
        const ventaData = JSON.stringify(venta);
        
        li.textContent = value;
        li.setAttribute('onclick', `selectOptionEliminarVenta('${value}', ${ventaData})`);
        ul.appendChild(li);
    });

    ul.classList.add('show');
}

async function validateValueOnRealTimeEliminarVenta(input, idMessageError, someHiddenIdInputsArray = null, otherInputsArray = null) {
    const idVentaIdNombreTecnico = input.value.trim();
    const messageError = document.getElementById(idMessageError);
    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`;
    const url = `${baseUrl}/dashboard-ventasIntermediadas/getVentaByIdVentaIdNombreTecnico`;

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

    if (idVentaIdNombreTecnico === "") {
        messageError.classList.remove('shown');
        clearInputs();
        return;
    }

    // Validar que el formato sea válido
    const regex = /^[BF][0-9]{3}-[0-9]{8} \| \d{8} - .+$/;
    
    if (!regex.test(idVentaIdNombreTecnico)) {
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
            body: JSON.stringify({ idVentaIdNombreTecnico: idVentaIdNombreTecnico }),
        });

        if (!response.ok) {
            messageError.classList.add('shown');
            clearInputs();
            return;
        }

        const data = await response.json();

        if (!data.ventaBuscada) {
            clearInputs();
            messageError.classList.add('shown');
            return;
        }

        messageError.classList.remove('shown');

        // Obtener el objeto ventaBuscada de la respuesta JSON
        const ventaBuscada = data.ventaBuscada;

        // Llenar los demás campos
        fechaEmisionVentaIntermediadaInputDelete.value = ventaBuscada.fechaVenta;
        horaEmisionVentaIntermediadaInputDelete.value = ventaBuscada.horaVenta;
        montoTotalInputDelete.value = ventaBuscada.montoTotal_VentaIntermediada;
        puntosGeneradosInputDelete.value = ventaBuscada.puntosGanados_VentaIntermediada;
        fechaCargadaInputDelete.value = ventaBuscada.fechaCargada;
        horaCargadaInputDelete.value = ventaBuscada.horaCargada;
        tipoCodigoClienteInputDelete.value = ventaBuscada.tipoCodigoCliente_VentaIntermediada;
        codigoClienteInputDelete.value = ventaBuscada.codigoCliente_VentaIntermediada;
        nombreClienteInputDelete.value = ventaBuscada.nombreCliente_VentaIntermediada;

        // Actualizar los inputs ocultos
        if (someHiddenIdInputsArray) {
            document.getElementById(someHiddenIdInputsArray[0]).value = ventaBuscada.idVentaIntermediada;
        }

        // Rellenar otros inputs visibles
        if (otherInputsArray) {
            document.getElementById(otherInputsArray[0]).value = ventaBuscada.fechaVenta;
            document.getElementById(otherInputsArray[1]).value = ventaBuscada.horaVenta;
            document.getElementById(otherInputsArray[2]).value = ventaBuscada.montoTotal_VentaIntermediada;
            document.getElementById(otherInputsArray[3]).value = ventaBuscada.puntosGanados_VentaIntermediada;
            document.getElementById(otherInputsArray[4]).value = ventaBuscada.fechaCargada;
            document.getElementById(otherInputsArray[5]).value = ventaBuscada.horaCargada;
            document.getElementById(otherInputsArray[6]).value = ventaBuscada.tipoCodigoCliente_VentaIntermediada;
            document.getElementById(otherInputsArray[7]).value = ventaBuscada.codigoCliente_VentaIntermediada;
            document.getElementById(otherInputsArray[8]).value = ventaBuscada.nombreCliente_VentaIntermediada;
        }
    } catch (error) {
        console.error(`Error inesperado en validateValueOnRealTimeEliminarVenta: ${error.message}`);
        clearInputs();
    }
}

// Manejador del evento de scroll
async function loadMoreOptionsEliminarVenta(event) {
    const optionsListUL = event.target;
    const threshold = 0.6; // 60% del contenido visible

    // Si el usuario ha llegado al final de la lista (se detecta el scroll)
    if (optionsListUL.scrollTop + optionsListUL.clientHeight >= optionsListUL.scrollHeight * threshold) {
        await loadPaginatedOptionsEliminarVenta(ventaTecnicoOptionsDelete.id);  // Cargar más opciones
    }
}

// Conectar el evento de scroll al `ul` para carga infinita
ventaTecnicoOptionsDelete.addEventListener('scroll', loadMoreOptionsEliminarVenta);

async function toggleOptionsEliminarVenta(input, idOptions) {
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
            await loadPaginatedOptionsEliminarVenta(idOptions);
        } 
    } else {
        filterOptionsEliminarVenta(input, idOptions);
    }

    optionsListUL.classList.add('show');
}

async function loadPaginatedOptionsEliminarVenta(idOptions) {
    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`;
    const url = `${baseUrl}/dashboard-ventasIntermediadas/getPaginatedVentas?page=${currentPageEliminarVenta}`;
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
        populateOptionsListEliminarVenta(optionsListUL, data.data);
        currentPageEliminarVenta++;
    } catch (error) {
        //console.error("Error al cargar técnicos:", error.message);
    }
}

function populateOptionsListEliminarVenta(optionsListUL, ventas) {
    const existingValues = new Set(
        Array.from(optionsListUL.children).map((li) => li.textContent.trim())
    );

    ventas.forEach((venta) => {
        const value = `${venta.idVentaIntermediada} | ${venta.idTecnico} - ${venta.nombreTecnico}`;
        const ventaData = JSON.stringify(venta);

        if (!existingValues.has(value)) {
            const li = document.createElement('li');
            li.textContent = value;
            li.setAttribute('onclick', `selectOptionEliminarVenta('${value}', ${ventaData})`);
            optionsListUL.appendChild(li);

            // Agrega el nuevo valor al Set
            existingValues.add(value);
        }
    });
}
/* FIN de funciones para manejar el input dinámico */

function validarHoraCargada() {
    // Obtén el valor del input como una hora (en formato "HH:mm:ss")
    const horaCargada = horaCargadaInputDelete.value;
    if (!horaCargada) {
        return false; // Si no hay valor, la validación falla
    }

    // Convierte la hora cargada y la hora actual en objetos Date
    const ahora = new Date(); // Hora actual
    const [horas, minutos, segundos] = horaCargada.split(":").map(Number);
    const horaCargadaDate = new Date(ahora.getFullYear(), ahora.getMonth(), ahora.getDate(), horas, minutos, segundos);

    // Calcula la diferencia en milisegundos
    const diferenciaEnMs = Math.abs(ahora - horaCargadaDate);

    // Convierte la diferencia de milisegundos a minutos
    const diferenciaEnMinutos = diferenciaEnMs / (1000 * 60); // 1000 milisegundos * 60 segundos en un minuto

    // 5 minutos
    const cincoMinutos = 5;

    // Retorna true si la diferencia es menor o igual a 5 minutos
    return diferenciaEnMinutos <= cincoMinutos;
}

function guardarModalEliminarVentaIntermediada(idModal, idForm) {
    if (ventaIntermediadaInputDelete.value && ventaTecnicoInputDelete.value) {
        if (validarHoraCargada()) {
            eliminarVentaSearchMessageError.classList.remove("shown");
            eliminarVentaMultiMessageErrorDelete.remove("shown");
            guardarModal(idModal, idForm);	
        }
        eliminarVentaMultiMessageErrorDelete.textContent = "No puede eliminar una venta pasado 5 minutos de ser cargada.";
        eliminarVentaMultiMessageErrorDelete.classList.add("shown");
    } else {
        eliminarVentaSearchMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        eliminarVentaSearchMessageError.classList.add("shown");
    }
}
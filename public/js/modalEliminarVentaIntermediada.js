let ventaTecnicoInputDelete = document.getElementById('ventaTecnicoInputDelete');
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

function selectOptionEliminarVenta(value, idInput, idOptions, fecha, hora, montoTotal, puntosGenerados, fechaCargada, horaCargada, tipoCodigoCliente, codigoCliente, nombreCliente) {
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
}

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
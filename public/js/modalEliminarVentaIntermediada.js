let ventaTecnicoInputDelete = document.getElementById('ventaTecnicoInputDelete');
let ventaIntermediadaInputDelete = document.getElementById('idVentaIntermediada');
let fechaEmisionVentaIntermediadaInputDelete = document.getElementById('fechaEmisionVentaIntermediadaInputDelete');
let horaEmisionVentaIntermediadaInputDelete = document.getElementById('horaEmisionVentaIntermediadaInputDelete');
let montoTotalInputDelete = document.getElementById('montoTotalInputDelete');
let puntosGeneradosInputDelete = document.getElementById('puntosGeneradosInputDelete');
let tipoCodigoClienteInputDelete = document.getElementById('tipoCodigoClienteInputDelete');
let codigoClienteInputDelete = document.getElementById('codigoClienteInputDelete');
let nombreClienteInputDelete = document.getElementById('nombreClienteInputDelete');
let eliminarVentaSearchMessageError = document.getElementById('eliminarVentaMessageError');

function selectOptionEliminarVenta(value, idInput, idOptions, fecha, hora, montoTotal, puntosGenerados, tipoCodigoCliente, codigoCliente, nombreCliente) {
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
        tipoCodigoClienteInputDelete.value = "";
        codigoClienteInputDelete.value = "";
        nombreClienteInputDelete.value = "";
    }

    eliminarVentaSearchMessageError.classList.remove('shown'); 
}

function guardarModalEliminarVentaIntermediada(idModal, idForm) {
    if (ventaIntermediadaInputDelete.value && ventaTecnicoInputDelete.value) {
        eliminarVentaSearchMessageError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        eliminarVentaSearchMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        eliminarVentaSearchMessageError.classList.add("shown");
    }
}
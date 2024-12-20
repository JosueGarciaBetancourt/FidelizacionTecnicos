let ventaIntermediadaInputDelete = document.getElementById('idVentaIntermediada');

function selectOptionEliminarVenta(value, idInput, idOptions) {
    //Colocar en el input la opción seleccionada 
    selectOption(value, idInput, idOptions); 

    // Extraer id y nombre del valor
    const [id] = value.split(' | ');
    
    // Actualizar los campos ocultos
    if (id) {
        ventaIntermediadaInputDelete.value = id;
    } else {
        ventaIntermediadaInputDelete.value = "";
    }

    nuevaVentaMessageError.classList.remove('shown'); 
}
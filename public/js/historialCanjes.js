function fillOtherFieldsDetalleHistorialCanje(objCanje) {
    const diasTranscurridosSufix = objCanje['diasTranscurridos_Canje'] > 2 ? " días transcurridos" : " día transcurrido";
    document.getElementById('codigoCanjeModalDetalleHistorialCanje').textContent = objCanje['idCanje'];
    document.getElementById('fechaHoraCanjeModalDetalleHistorialCanje').textContent = objCanje['fechaHora_Canje'];
    document.getElementById('diasTranscurridosCanjeModalDetalleHistorialCanje').textContent = objCanje['diasTranscurridos_Canje'] + diasTranscurridosSufix;
    document.getElementById('numeroComprobanteModalDetalleHistorialCanje').value = objCanje['idVentaIntermediada'];
    document.getElementById('fechaHoraEmisionComprobanteModalDetalleHistorialCanje').value = objCanje['fechaHoraEmision_VentaIntermediada'];
    document.getElementById('puntosComprobanteModalDetalleHistorialCanje').value = objCanje['puntosComprobante_Canje'];
    document.getElementById('puntosCanjeadosModalDetalleHistorialCanje').value = objCanje['puntosCanjeados_Canje'];
    document.getElementById('puntosRestantesComprobanteModalDetalleHistorialCanje').value = objCanje['puntosRestantes_Canje'];
    document.getElementById('comentarioComprobanteModalDetalleHistorialCanje').value = objCanje['comentario_Canje'] || "";
}

function fillTableDetalleHistorialCanje(detallesCanjes) {
    if (!Array.isArray(detallesCanjes) || detallesCanjes.length === 0) {
        console.warn('No hay datos para llenar la tabla');
        return;
    }

    const tblBodyDetalleHistorialCanje = document.querySelector('#tblDetalleHistorialCanje tbody');
    const celdaTotalPuntosRecompensas = document.getElementById('celdaTotalPuntos');
    var total_puntos_recompensas = 0;

    tblBodyDetalleHistorialCanje.innerHTML = ''; // Limpiar la tabla antes de agregar las filas
    detallesCanjes.forEach((detalle) => {
        const row = document.createElement('tr');
        
        // Crear celdas con la información de cada detalle de canje
        const tdNumero = document.createElement('td');
        tdNumero.classList.add('celda-centered');
        tdNumero.textContent = detalle.index;
        row.appendChild(tdNumero);

        const tdCodigoRecompensa = document.createElement('td');
        tdCodigoRecompensa.classList.add('celda-centered');
        tdCodigoRecompensa.textContent = detalle.idRecompensa;
        row.appendChild(tdCodigoRecompensa);

        const tdTipo = document.createElement('td');
        tdTipo.classList.add('celda-centered');
        tdTipo.textContent = detalle.nombre_TipoRecompensa;
        row.appendChild(tdTipo);

        const tdDescripcion = document.createElement('td');
        tdDescripcion.classList.add('celda-centered');
        tdDescripcion.textContent = detalle.descripcionRecompensa;
        row.appendChild(tdDescripcion);

        const tdCantidad = document.createElement('td');
        tdCantidad.classList.add('celda-centered');
        tdCantidad.textContent = detalle.cantidad;
        row.appendChild(tdCantidad);

        const tdCostoPuntos = document.createElement('td');
        tdCostoPuntos.classList.add('celda-centered');
        tdCostoPuntos.textContent = detalle.costoRecompensa;
        row.appendChild(tdCostoPuntos);

        const tdPuntosTotales = document.createElement('td');
        tdPuntosTotales.classList.add('celda-centered');
        tdPuntosTotales.textContent = detalle.puntosTotales;
        row.appendChild(tdPuntosTotales);

        // Agregar la fila a la tabla
        tblBodyDetalleHistorialCanje.appendChild(row);

        total_puntos_recompensas += detalle.puntosTotales
    });

    celdaTotalPuntosRecompensas.textContent = total_puntos_recompensas;
}

function returnObjCanjeById(idCanje, canjesDB) {
    objCanje = canjesDB.find(canje => canje.idCanje === idCanje) || null;
    return objCanje;
}

async function objCanjeAndDetailsByIdCanjeFetch(idCanje) {
    const url = `${baseUrlMAIN}/dashboard-canjes/canjeAndDetails/${idCanje}`;

    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(await response.text());
        }

        console.log(response);
        
        const { objCanje, detallesCanjes } = await response.json();

        console.log("Objeto Canje:", objCanje);
        console.log("Detalles Canjes:", detallesCanjes);
        
        // Llenar campos del modal
        fillOtherFieldsDetalleHistorialCanje(objCanje);

        // Llenar la tabla con los detalles de las recompensas
        if (detallesCanjes && detallesCanjes.length > 0) {
            fillTableDetalleHistorialCanje(detallesCanjes);
            
            const objCanje = {
                idCanje: idCanje,
                fechaHora_Canje: document.getElementById('fechaHoraCanjeModalDetalleHistorialCanje').textContent,
                diasTranscurridos_Canje: document.getElementById('diasTranscurridosCanjeModalDetalleHistorialCanje').textContent.split(' ')[0],
                idVentaIntermediada: document.getElementById('numeroComprobanteModalDetalleHistorialCanje').value,
                fechaHoraEmision_VentaIntermediada: document.getElementById('fechaHoraEmisionComprobanteModalDetalleHistorialCanje').value,
                puntosComprobante_Canje: document.getElementById('puntosComprobanteModalDetalleHistorialCanje').value,
                puntosCanjeados_Canje: document.getElementById('puntosCanjeadosModalDetalleHistorialCanje').value,
                puntosRestantes_Canje: document.getElementById('puntosRestantesComprobanteModalDetalleHistorialCanje').value,
                comentario_Canje: document.getElementById('comentarioComprobanteModalDetalleHistorialCanje').value,
            };

            StorageHelper.saveModalDataToStorage('currentCanje', objCanje);
            StorageHelper.saveModalDataToStorage('currentCanjeDetails', detallesCanjes);
        }
        
        // Abrir el modal
        justOpenModal('modalDetalleHistorialCanje');
    } catch (error) {
        console.error('Error al realizar la consulta al backend para obtener las recompensas del canje:', error.message);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Cargar el modal si es que estaba abierto
    try {
        const persistedCanje = StorageHelper.loadModalDataFromStorage('currentCanje');
        const persistedCanjeDetails = StorageHelper.loadModalDataFromStorage('currentCanjeDetails');
        
        if (persistedCanje && persistedCanjeDetails) {
            const objCanje = persistedCanje;
            const detallesCanjes = persistedCanjeDetails;
    
            fillOtherFieldsDetalleHistorialCanje(objCanje);
            fillTableDetalleHistorialCanje(detallesCanjes);
    
            justOpenModal('modalDetalleHistorialCanje');
        } 
    } catch (error) {
        console.log("Error al cargar la data del Canje: ", error);
    }
});

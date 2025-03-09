function fillOtherFieldsDetalleSolicitudCanje(objSolicitudCanje) {
    const userInfoContainer = document.getElementById('userInfoContainer');
    userInfoContainer.style.display = 'none';

    const estadoH5 = document.getElementById('estadoSolicitudCanjeModalDetalleSolicitudCanje');
    const diasTranscurridosSufix = objSolicitudCanje['diasTranscurridos_SolicitudCanje'] >= 2 ? " días transcurridos" : " día transcurrido";

    document.getElementById('codigoModalDetalleSolicitudCanje').textContent = objSolicitudCanje['idSolicitudCanje'];
    document.getElementById('fechaHoraModalDetalleSolicitudCanje').textContent = objSolicitudCanje['fechaHora_SolicitudCanje'];
    document.getElementById('diasTranscurridosModalDetalleSolicitudCanje').textContent = objSolicitudCanje['diasTranscurridos_SolicitudCanje'] + diasTranscurridosSufix;
    
    document.getElementById('numeroComprobanteModalDetalleSolicitudCanje').value = objSolicitudCanje['idVentaIntermediada'];
    document.getElementById('fechaHoraEmisionComprobanteModalDetalleSolicitudCanje').value = objSolicitudCanje['fechaHoraEmision_VentaIntermediada'];
    document.getElementById('puntosActualesComprobanteModalDetalleSolicitudCanje').value = objSolicitudCanje['puntosActuales_SolicitudCanje'];
    document.getElementById('puntosCanjeadosModalDetalleSolicitudCanje').value = objSolicitudCanje['puntosCanjeados_SolicitudCanje'];
    document.getElementById('puntosRestantesComprobanteModalDetalleSolicitudCanje').value = objSolicitudCanje['puntosRestantes_SolicitudCanje'];
    estadoH5.textContent = objSolicitudCanje['nombreEstado'] || "";

    estadoH5.classList.remove('estadoAprobado', 'estadoRechazado', 'estadoPendiente');
    
    const estado = (estadoH5.textContent).toLowerCase();
    
    if (estado === "aprobado") {
        estadoH5.classList.add('estadoAprobado');
    } else if (estado === "rechazado") {
        estadoH5.classList.add('estadoRechazado');
    } else {
        estadoH5.classList.add('estadoPendiente');
    }
    
    if (objSolicitudCanje['userName'] && objSolicitudCanje['comentario_SolicitudCanje']) {
        userInfoContainer.style.display = 'block';
        document.getElementById('userModalDetalleSolicitudCanje').value = objSolicitudCanje['userName'] || "";
        document.getElementById('comentarioComprobanteModalDetalleSolicitudCanje').value = objSolicitudCanje['comentario_SolicitudCanje'] || "";
    }
}

function fillTableDetalleSolicitudCanje(detallesSolicitudesCanjes) {
    if (!Array.isArray(detallesSolicitudesCanjes) || detallesSolicitudesCanjes.length === 0) {
        console.warn('No hay datos para llenar la tabla');
        return;
    }

    const tblBodyDetalleHistorialCanje = document.querySelector('#tblDetalleSolicitudCanje tbody');
    const celdaTotalPuntosRecompensas = document.getElementById('celdaTotalPuntos');
    var total_puntos_recompensas = 0;

    tblBodyDetalleHistorialCanje.innerHTML = ''; // Limpiar la tabla antes de agregar las filas
    detallesSolicitudesCanjes.forEach((detalle) => {
        const row = document.createElement('tr');
        
        // Crear celdas con la información de cada detalle de canje
        const tdNumero = document.createElement('td');
        tdNumero.classList.add('celda-centered');
        tdNumero.textContent = detalle.index;;
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

async function objSolicitudCanjeAndDetailsByIdSolicitudCanjeFetch(idSolicitudCanje) {
    const url = `${baseUrlMAIN}/dashboard-solicitudesCanjes/solicitudCanjeAndDetails/${idSolicitudCanje}`;

    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(await response.text());
        }

        const { objSolicitudCanje, detallesSolicitudesCanjes } = await response.json();

        console.log("objSolicitudCanje:", objSolicitudCanje);
        
        // Llenar la tabla con los detalles de las recompensas
        if (detallesSolicitudesCanjes && detallesSolicitudesCanjes.length > 0) {
            fillOtherFieldsDetalleSolicitudCanje(objSolicitudCanje);
            fillTableDetalleSolicitudCanje(detallesSolicitudesCanjes);
            StorageHelper.saveModalDataToStorage('currentSolicitudCanje', objSolicitudCanje);
            StorageHelper.saveModalDataToStorage('currentSolicitudCanjeDetails', detallesSolicitudesCanjes);
        }
        
        justOpenModal('modalDetalleSolicitudCanje');
    } catch (error) {
        console.error('Error al realizar la consulta al backend para obtener las recompensas del canje:', error.message);
    }
}

/* function returnObjSolicitudCanjeById(idSolicitudCanje, solicitudesCanjeDB) {
    const objSolicitudCanje = solicitudesCanjeDB.find(canje => canje.idSolicitudCanje === idSolicitudCanje) || null;
    return objSolicitudCanje;
} */

document.addEventListener('DOMContentLoaded', function() {
    try {
        // Intentar cargar datos persistidos para la Solicitud Canje
        const objSolicitudCanje = StorageHelper.loadModalDataFromStorage('currentSolicitudCanje');
        const persistedSolicitudCanjeDetails = StorageHelper.loadModalDataFromStorage('currentSolicitudCanjeDetails');

        if (objSolicitudCanje && persistedSolicitudCanjeDetails) {
            // Llenar campos y tabla con los datos cargados
            fillOtherFieldsDetalleSolicitudCanje(objSolicitudCanje);
            fillTableDetalleSolicitudCanje(persistedSolicitudCanjeDetails);

            // Abrir el modal correspondiente
            justOpenModal('modalDetalleSolicitudCanje');
        }
    } catch (error) {
        console.error('Error al cargar la data de la Solicitud Canje:', error);
    }
});
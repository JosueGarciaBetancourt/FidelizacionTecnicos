function fillOtherFieldsDetalleSolicitudCanje(objSolicitudCanje) {
    const userInfoContainer = document.getElementById('userInfoContainer');
    userInfoContainer.style.display = 'none';

    const estadoH5 = document.getElementById('estadoSolicitudCanjeModalDetalleSolicitudCanje');
    const diasTranscurridosSufix = objSolicitudCanje['diasTranscurridos_SolicitudCanje'] > 2 ? " días transcurridos" : " día transcurrido";

    document.getElementById('codigoModalDetalleSolicitudCanje').textContent = objSolicitudCanje['idSolicitudCanje'];
    document.getElementById('fechaHoraModalDetalleSolicitudCanje').textContent = objSolicitudCanje['fechaHora_SolicitudCanje'];
    document.getElementById('diasTranscurridosModalDetalleSolicitudCanje').textContent = objSolicitudCanje['diasTranscurridos_SolicitudCanje'] + diasTranscurridosSufix;
    
    document.getElementById('numeroComprobanteModalDetalleSolicitudCanje').value = objSolicitudCanje['idVentaIntermediada'];
    document.getElementById('fechaHoraEmisionComprobanteModalDetalleSolicitudCanje').value = objSolicitudCanje['fechaHoraEmision_VentaIntermediada'];
    document.getElementById('puntosComprobanteModalDetalleSolicitudCanje').value = objSolicitudCanje['puntosComprobante_SolicitudCanje'];
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

function openModalDetalleSolicitudCanje(button, solicitudesCanjeDB) {
    const fila = button.closest('tr');
    const celdaCodigoSolicitudCanje = fila.getElementsByClassName('idSolicitudCanje')[0]; 
    const codigo = celdaCodigoSolicitudCanje.innerText;
    const objSolicitudCanje = returnObjSolicitudCanjeById(codigo, solicitudesCanjeDB);

    // LLenar campos del formulario de detalle canje
    fillOtherFieldsDetalleSolicitudCanje(objSolicitudCanje);

    StorageHelper.saveModalDataToStorage('currentSolicitudCanje', objSolicitudCanje);

    // Realizar la consulta al backend, llenar tabla de recompensas y abrir el modal
    getDetalleSolicitudCanjeByIdCanjeFetch(objSolicitudCanje['idSolicitudCanje']);
}

function returnObjSolicitudCanjeById(idSolicitudCanje, solicitudesCanjeDB) {
    const objSolicitudCanje = solicitudesCanjeDB.find(canje => canje.idSolicitudCanje === idSolicitudCanje) || null;
    return objSolicitudCanje;
}

async function getDetalleSolicitudCanjeByIdCanjeFetch(idSolicitudCanje) {
    //const url = `http://localhost/FidelizacionTecnicos/public/dashboard-canjes/solicitudCanje/${idSolicitudCanje}`;
    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`; // Esto adaptará la URL al dominio actual
    const url = `${baseUrl}/dashboard-canjes/solicitudCanje/${idSolicitudCanje}`; 
    //console.warn("fetch", url);

    try {
        const response = await fetch(url);
        /*console.log("Fetching URL:", url);
        console.log('Response Status:', response.status);
        console.log('Response Headers:', response.headers.get('Content-Type'));*/

        if (!response.ok) {
            throw new Error(await response.text());
        }

        const detallesSolicitudesCanjes = await response.json();
        
        // Llenar la tabla con los detalles de las recompensas
        if (detallesSolicitudesCanjes && detallesSolicitudesCanjes.length > 0) {
            fillTableDetalleSolicitudCanje(detallesSolicitudesCanjes);
            StorageHelper.saveModalDataToStorage('currentSolicitudCanjeDetails', detallesSolicitudesCanjes);
        }
        
        // Abrir el modal
        justOpenModal('modalDetalleSolicitudCanje');
    } catch (error) {
        console.error('Error al realizar la consulta al backend para obtener las recompensas de la solicitud canje:', error.message);
    }
}

function aprobarSolicitudCanje(idSolicitudCanje){
    // Mostrar el modal y esperar la respuesta del usuario
    openConfirmSolicitudCanjeModal('modalConfirmActionAprobarSolicitudCanje').then((response) => {
        if (response.answer) {
            if (response.comment) {
                aprobarSolicitud(idSolicitudCanje, response.comment); 
                return;
            }
        } 
    });
}

function rechazarSolicitudCanje(idSolicitudCanje){
    // Mostrar el modal y esperar la respuesta del usuario
    openConfirmSolicitudCanjeModal('modalConfirmActionRechazarSolicitudCanje').then((response) => {
        if (response.answer) {
            if (response.comment) {
                rechazarSolicitud(idSolicitudCanje, response.comment); 
            }
            return;
        }
    });
}

async function aprobarSolicitud(idSolicitudCanje, comentario) {
    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`;
    const url = `${baseUrl}/dashboard-canjes/solicitudCanje/aprobar/${idSolicitudCanje}`;

    try {
        // Obtener el token CSRF desde el contenido de la página
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const response = await fetch(url, {
            method: 'POST', // Cambiado a POST para enviar datos
            headers: {
                'Content-Type': 'application/json', // Importante para enviar JSON
                'X-CSRF-TOKEN': csrfToken, // Agregar el token CSRF aquí
            },
            body: JSON.stringify({ comentario: comentario }), // Enviando el comentario
        });

        if (!response.ok) {
            throw new Error(await response.text());
        }

        const mensaje = await response.json();
        console.log(mensaje);

        // Recargar la página después de que la solicitud se haya procesado correctamente
        location.reload();
    } catch (error) {
        console.error('Error al realizar la consulta al backend para aprobar la solicitud:', error.message);
    }
}

async function rechazarSolicitud(idSolicitudCanje, comentario) {
    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`;
    const url = `${baseUrl}/dashboard-canjes/solicitudCanje/rechazar/${idSolicitudCanje}`;

    try {
        // Obtener el token CSRF desde el contenido de la página
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const response = await fetch(url, {
            method: 'POST', // Cambiado a POST para enviar datos
            headers: {
                'Content-Type': 'application/json', // Importante para enviar JSON
                'X-CSRF-TOKEN': csrfToken, // Agregar el token CSRF aquí
            },
            body: JSON.stringify({ comentario: comentario }), // Enviando el comentario
        });

        if (!response.ok) {
            throw new Error(await response.text());
        }

        const mensaje = await response.json();
        console.log(mensaje);

        // Recargar la página después de que la solicitud se haya procesado correctamente
        location.reload();
    } catch (error) {
        console.error('Error al realizar la consulta al backend para rechazar la solicitud:', error.message);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Cargar el modal si es que estaba abierto
    try {
        const objSolicitudCanje = StorageHelper.loadModalDataFromStorage('currentSolicitudCanje');
        const persistedSolicitudCanjeDetails = StorageHelper.loadModalDataFromStorage('currentSolicitudCanjeDetails');
        
        if (objSolicitudCanje && persistedSolicitudCanjeDetails) {
            const objetoSolicitudCanje = objSolicitudCanje;
            const detallesSolicitudCanje = persistedSolicitudCanjeDetails;
    
            fillOtherFieldsDetalleSolicitudCanje(objetoSolicitudCanje)
            fillTableDetalleSolicitudCanje(detallesSolicitudCanje);
    
            justOpenModal('modalDetalleSolicitudCanje');
        } 
    } catch (error) {
        console.log("Error al cargar la data de la Solicitud Canje: ", error);
    }
});

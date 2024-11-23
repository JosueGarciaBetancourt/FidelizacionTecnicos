
function fillOtherFieldsDetalleSolicitudCanje(objSolicitudCanje) {
    const diasTranscurridosSufix = objSolicitudCanje['diasTranscurridos_SolicitudCanje'] > 2 ? " días transcurridos" : " día transcurrido";
    document.getElementById('codigoModalDetalleSolicitudCanje').textContent = objSolicitudCanje['idSolicitudCanje'];
    document.getElementById('fechaHoraModalDetalleSolicitudCanje').textContent = objSolicitudCanje['fechaHora_SolicitudCanje'];
    document.getElementById('diasTranscurridosModalDetalleSolicitudCanje').textContent = objSolicitudCanje['diasTranscurridos_SolicitudCanje'] + diasTranscurridosSufix;
    document.getElementById('numeroComprobanteModalDetalleSolicitudCanje').value = objSolicitudCanje['idVentaIntermediada'];
    document.getElementById('fechaHoraEmisionComprobanteModalDetalleSolicitudCanje').value = objSolicitudCanje['fechaHoraEmision_VentaIntermediada'];
    document.getElementById('puntosComprobanteModalDetalleSolicitudCanje').value = objSolicitudCanje['puntosComprobante_SolicitudCanje'];
    document.getElementById('puntosCanjeadosModalDetalleSolicitudCanje').value = objSolicitudCanje['puntosCanjeados_SolicitudCanje'];
    document.getElementById('puntosRestantesComprobanteModalDetalleSolicitudCanje').value = objSolicitudCanje['puntosRestantes_SolicitudCanje'];
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

function openModalSolicitudCanje(button, solicitudesCanjeDB) {
    const fila = button.closest('tr');
    const celdaCodigoSolicitudCanje = fila.getElementsByClassName('idSolicitudCanje')[0]; 
    const codigo = celdaCodigoSolicitudCanje.innerText;
    const objSolicitudCanje = returnObjSolicitudCanjeById(codigo, solicitudesCanjeDB);

    // LLenar campos del formulario de detalle canje
    fillOtherFieldsDetalleSolicitudCanje(objSolicitudCanje);

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
    console.warn("fetch", url);

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
        }
        
        // Abrir el modal
        var modal = document.getElementById('modalDetalleSolicitudCanje');
        modal.style.display = 'block';
        setTimeout(function() {
            modal.style.opacity = 1; // Hacer el modal visible de forma gradual
            modal.querySelector('.modal-dialog').classList.add('open');
        }, 50); // Pequeño retraso para asegurar la transición CSS
        document.body.style.overflow = 'hidden'; // Evita el scroll de fondo cuando está abierto el modal
    } catch (error) {
        console.error('Error al realizar la consulta al backend para obtener las recompensas de la solicitud canje:', error.message);
    }
}

async function aprobarSolicitud(idSolicitudCanje) {
    //const url = `http://localhost/FidelizacionTecnicos/public/dashboard-canjes/solicitudCanje/${idSolicitudCanje}`;
    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`; // Esto adaptará la URL al dominio actual
    const url = `${baseUrl}/dashboard-canjes/solicitudCanje/aprobar/${idSolicitudCanje}`; 
    console.warn("fetch", url);

    try {
        const response = await fetch(url);
        /*console.log("Fetching URL:", url);
        console.log('Response Status:', response.status);
        console.log('Response Headers:', response.headers.get('Content-Type'));*/

        if (!response.ok) {
            throw new Error(await response.text());
        }

        const mensaje = await response.json();
        console.log(mensaje);

        // Recargar la página después de que la solicitud se haya procesado correctamente
        location.reload(); // Recarga la página actual
        
        /*
        // Abrir el modal
        /var modal = document.getElementById('modalDetalleSolicitudCanje');
        modal.style.display = 'block';
        setTimeout(function() {
            modal.style.opacity = 1; // Hacer el modal visible de forma gradual
            modal.querySelector('.modal-dialog').classList.add('open');
        }, 50); // Pequeño retraso para asegurar la transición CSS
        document.body.style.overflow = 'hidden'; // Evita el scroll de fondo cuando está abierto el modal
        */
    } catch (error) {
        console.error('Error al realizar la consulta al backend para obtener las recompensas de la solicitud canje:', error.message);
    }
}

async function rechazarSolicitud(idSolicitudCanje) {
    //const url = `http://localhost/FidelizacionTecnicos/public/dashboard-canjes/solicitudCanje/${idSolicitudCanje}`;
    const baseUrl = `${window.location.origin}/FidelizacionTecnicos/public`; // Esto adaptará la URL al dominio actual
    const url = `${baseUrl}/dashboard-canjes/solicitudCanje/rechazar/${idSolicitudCanje}`; 
    console.warn("fetch", url);

    try {
        const response = await fetch(url);
        /*console.log("Fetching URL:", url);
        console.log('Response Status:', response.status);
        console.log('Response Headers:', response.headers.get('Content-Type'));*/

        if (!response.ok) {
            throw new Error(await response.text());
        }

        const mensaje = await response.json();
        
        console.log(mensaje);
        /*
        // Llenar la tabla con los detalles de las recompensas
        if (mensaje) {
            fillTableDetalleSolicitudCanje(detallesSolicitudesCanjes);
        }
        */
        /*
        // Abrir el modal
        /var modal = document.getElementById('modalDetalleSolicitudCanje');
        modal.style.display = 'block';
        setTimeout(function() {
            modal.style.opacity = 1; // Hacer el modal visible de forma gradual
            modal.querySelector('.modal-dialog').classList.add('open');
        }, 50); // Pequeño retraso para asegurar la transición CSS
        document.body.style.overflow = 'hidden'; // Evita el scroll de fondo cuando está abierto el modal
        */
    } catch (error) {
        console.error('Error al realizar la consulta al backend para obtener las recompensas de la solicitud canje:', error.message);
    }
}
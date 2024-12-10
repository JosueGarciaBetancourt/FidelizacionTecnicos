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

    console.log(detallesCanjes);

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

function saveCanjeDataToStorage(objCanje, detallesCanjes) {
    localStorage.setItem('currentCanje', JSON.stringify(objCanje));
    localStorage.setItem('currentCanjeDetails', JSON.stringify(detallesCanjes));
}

function returnObjCanjeById(idCanje, canjesDB) {
    objCanje = canjesDB.find(canje => canje.idCanje === idCanje) || null;
    return objCanje;
}

function openModalDetalleHistorialCanje(button, canjesDB) {
    const fila = button.closest('tr');
    const celdaCodigoCanje = fila.getElementsByClassName('idCanje')[0]; 
    const codigo = celdaCodigoCanje.innerText;
    const objCanje = returnObjCanjeById(codigo, canjesDB);

    fillOtherFieldsDetalleHistorialCanje(objCanje);
    
    // Realizar la consulta al backend, llenar tabla de recompensas y abrir el modal
    getDetalleCanjeByIdCanjeFetch(objCanje['idCanje']);
}

// Usage
async function getDetalleCanjeByIdCanjeFetch(idCanje) {
    //const url = `http://localhost/FidelizacionTecnicos/public/dashboard-canjes/historialCanje/${idCanje}`;
    const baseUrl = `${window.location.origin}`; // Esto adaptará la URL al dominio actual
    const url = `${baseUrl}/dashboard-canjes/historialCanje/${idCanje}`;
    console.warn("fetch", url);

    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(await response.text());
        }

        const detallesCanjes = await response.json();
        
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

            saveCanjeDataToStorage(objCanje, detallesCanjes);
        }
        
        // Abrir el modal
        var modal = document.getElementById('modalDetalleHistorialCanje');
        modal.style.display = 'block';
        setTimeout(function() {
            modal.style.opacity = 1; // Hacer el modal visible de forma gradual
            modal.querySelector('.modal-dialog').classList.add('open');
        }, 50); // Pequeño retraso para asegurar la transición CSS
        document.body.style.overflow = 'hidden'; // Evita el scroll de fondo cuando está abierto el modal
    } catch (error) {
        console.error('Error al realizar la consulta al backend para obtener las recompensas del canje:', error.message);
    }
}

// Add event listeners on page load
document.addEventListener('DOMContentLoaded', function() {
    loadPersistedCanjeData();
});

function openPersistedModal() {
    var modal = document.getElementById('modalDetalleHistorialCanje');
    modal.style.display = 'block';
    setTimeout(function() {
        modal.style.opacity = 1;
        modal.querySelector('.modal-dialog').classList.add('open');
    }, 50);
    document.body.style.overflow = 'hidden';
}

function clearPersistedData() {
    // Clear data when modal is closed
    localStorage.removeItem('currentCanje');
    localStorage.removeItem('currentCanjeDetails');
    console.log('Cleaning persisted fetch modal data');
}

function loadPersistedCanjeData() {
    const persistedCanje = localStorage.getItem('currentCanje');
    const persistedCanjeDetails = localStorage.getItem('currentCanjeDetails');

    if (persistedCanje && persistedCanjeDetails) {
        const objCanje = JSON.parse(persistedCanje);
        const detallesCanjes = JSON.parse(persistedCanjeDetails);

        // Restore modal fields
        fillOtherFieldsDetalleHistorialCanje(objCanje);
        fillTableDetalleHistorialCanje(detallesCanjes);

        // Reopen the modal
        openPersistedModal();
    }
}

function closeFetchModal(modalId) {
    console.log("Cerrando fetch modal");
    var modal = document.getElementById(modalId);
    if (modal) {
        modal.querySelector('.modal-dialog').classList.remove('open');
        setTimeout(function() {
            modal.style.display = 'none';
        }, 300); // Espera 0.3 segundos (igual a la duración de la transición CSS)
        
        // Remove specific modal data from localStorage
        clearPersistedData();
    }
}
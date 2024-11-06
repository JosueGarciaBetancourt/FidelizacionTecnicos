let botonesVerDetalle = document.querySelectorAll('.btnDetalle');

function openModalDetalleHistorialCanje(button, canjesDB) {
    const fila = button.closest('tr');
    const celdas = fila.getElementsByTagName('td');

    const numero = celdas[0].innerText;
    const codigo = celdas[1].innerText;
    const fechaHora = celdas[2].innerText;
    const comprobante = celdas[3].innerText;
    const fechaHoraEmision = celdas[4].innerText;
    const diasTranscurridos = celdas[5].innerText;
    const puntosComprobante = celdas[6].innerText;
    const puntosCanjeados = celdas[7].innerText;
    const puntosRestantes = celdas[8].innerText;

    document.getElementById('codigoCanjeModalDetalleHistorialCanje').innerText = codigo;

	// Abrir modal (sin guardar en local storage)
	var modal = document.getElementById('modalDetalleHistorialCanje');
	modal.style.display = 'block';
	setTimeout(function() {
		modal.style.opacity = 1; // Hacer el modal visible de forma gradual
		modal.querySelector('.modal-dialog').classList.add('open');
	}, 50); // Pequeño retraso para asegurar la transición CSS
	document.body.style.overflow = 'hidden'; // Evita el scroll de fondo cuando está abierto el modal
}

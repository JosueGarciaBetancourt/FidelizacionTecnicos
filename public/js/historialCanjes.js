function openModalDetalleHistorialCanje(button, canjesDB) {
    const fila = button.closest('tr');
    const celdaCodigoCanje = fila.getElementsByClassName('idCanje')[0]; 
    const codigo = celdaCodigoCanje.innerText;
    const objCanje = returnObjCanjeById(codigo, canjesDB);
    console.log(objCanje);
    
    document.getElementById('codigoCanjeModalDetalleHistorialCanje').innerText = objCanje['idCanje'];
    document.getElementById('fechaHoraCanjeModalDetalleHistorialCanje').innerText = objCanje['fechaHora_Canje'];
    document.getElementById('numeroComprobanteModalDetalleHistorialCanje').value = objCanje['idVentaIntermediada'];

	// Abrir modal (sin guardar en local storage)
	var modal = document.getElementById('modalDetalleHistorialCanje');
	modal.style.display = 'block';
	setTimeout(function() {
		modal.style.opacity = 1; // Hacer el modal visible de forma gradual
		modal.querySelector('.modal-dialog').classList.add('open');
	}, 50); // Pequeño retraso para asegurar la transición CSS
	document.body.style.overflow = 'hidden'; // Evita el scroll de fondo cuando está abierto el modal
}

function returnObjCanjeById(idCanje, canjesDB) {
    for (const key in canjesDB) {
        if (canjesDB[key]['idCanje'] === idCanje) {
            return canjesDB[key]; 
        }
    }
    return null; // Retornar null si no se encuentra el objeto
}
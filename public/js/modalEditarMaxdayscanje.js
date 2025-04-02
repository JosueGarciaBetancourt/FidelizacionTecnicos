let editarMaxdayscanjeInput = document.getElementById('editarMaxdayscanjeInput');
let editarMaxdayscanjeError = document.getElementById('editarMaxdayscanjeError');

function guardarModalEditarMaxdayscanje(idModal, idForm) {
    const newMaxdayscanje = editarMaxdayscanjeInput.value;
    if (!newMaxdayscanje || newMaxdayscanje == null || newMaxdayscanje == 0)  {
        editarMaxdayscanjeError.classList.add('shown');
        return;
    }  
    
    if (newMaxdayscanje < diasAgotarVentaIntermediadaNotificacionMAIN) {
        const msg = `Los días máximos de registro de venta (${newMaxdayscanje}) deben ser mayores a los días para notificar \
                    a un técnico sobre agotamiento de canje (${diasAgotarVentaIntermediadaNotificacionMAIN}), ver Configuración.`;
        openErrorModal("errorModalVentaIntermediada", msg);
        return;
    }   

    editarMaxdayscanjeError.classList.remove('shown');

    document.getElementById("idMessageConfirmModal").innerText = `¿Está seguro de esta acción? Algunas ventas y solicitudes de canjes\
                                                                actualizarán sus estados a Tiempo Agotado`

    // Confirmar acción 
    openConfirmModal('modalConfirmActionVentaIntermediada').then((response) => {
        if (response) {
            guardarModal(idModal, idForm);
            return;
        }
    });
}
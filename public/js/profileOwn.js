function openModalEditarUsuario(button, usersDB) {
    const fila = button.closest('tr');
    const celdaEmail = fila.getElementsByClassName('email')[0]; 
    const email = celdaEmail.innerText;

    const objCanje = returnObjUserByEmail(email, usersDB);
    console.log("EDITAR: ", objCanje);

    openModal("modalEditarUsuario");
    /*// LLenar campos del formulario de detalle canje
    fillOtherFieldsDetalleHistorialCanje(objCanje);
    
    // Realizar la consulta al backend, llenar tabla de recompensas y abrir el modal
    getDetalleCanjeByIdCanjeFetch(objCanje['idCanje']);*/
}

function openModalEliminarUsuario(button, usersDB) {
    const fila = button.closest('tr');
    const celdaEmail = fila.getElementsByClassName('email')[0]; 
    const email = celdaEmail.innerText;

    const objCanje = returnObjUserByEmail(email, usersDB);
    console.log("ELIMINAR: ", objCanje);

    openConfirmModal('modalConfirmActionEliminarUsuario').then((response) => {
        if (response.answer) {
            console.log('Eliminando usuario...');
            // Recargar la página después de que la solicitud se haya procesado correctamente
            location.reload();
            return;
        } 
        console.log(response.answer);
    });
}

function returnObjUserByEmail(email, usersDB) {
    objUser = usersDB.find(canje => canje.email === email) || null;
    return objUser;
}
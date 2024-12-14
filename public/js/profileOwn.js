function actualizarComponentesSegunTipoUsuario(isAdmin) {
    // Obtén el componente de selección de perfil
    const perfilUsuarioInput = document.getElementById('perfilUsuarioInputEditarUsuario'); 
    const perfilUsuarioContainer = document.getElementById('perfilUsuarioSelectEditarUsuario'); 
    const perfilUsuarioSpan = perfilUsuarioContainer.querySelector('span');

    console.log(perfilUsuarioInput.className); 
    console.log(perfilUsuarioContainer.className);
    console.log(perfilUsuarioSpan.className);

    if (isAdmin) {
        // Si es un admin, se añaden las clases y atributos correspondientes
        perfilUsuarioInput.classList.add('onlySelectInput', 'long', 'blocked');
        perfilUsuarioSpan.classList.add('blocked');
        perfilUsuarioInput.setAttribute('disabled', true); // Desactivar el campo
        perfilUsuarioContainer.classList.add('noFocusBorder', 'blocked');
    } else {
        console.log("EL USUARIO CLICKEADO NO ES ADMIN");
        // Si no es admin, se usan las clases básicas
        perfilUsuarioInput.classList.remove('blocked');
        perfilUsuarioSpan.classList.remove('blocked');
        perfilUsuarioInput.removeAttribute('disabled');
        perfilUsuarioContainer.classList.remove('noFocusBorder', 'blocked');
        perfilUsuarioInput.classList.add('onlySelectInput', 'long');
        perfilUsuarioInput.classList.add('onlySelectInput', 'long');
    }
}

function fillFieldsEditarUsuario(objUser) {
    if (!objUser) {
        return;
    }

    const perfil = objUser.nombre_PerfilUsuario;
    const viewIcons = Array.from(document.getElementsByClassName('viewPasswordIcon'));  
  
    viewIcons.forEach(icon => {
        icon.textContent = "visibility_off";
    });

    document.getElementById('idUser').value = objUser.id;
    document.getElementById('nameInputEditarUsuario').value = objUser.name;
    document.getElementById('emailInputEditarUsuario').value = objUser.email;
    document.getElementById('perfilUsuarioInputEditarUsuario').value = perfil;
    document.getElementById('passwordInputEditarUsuario').value = "";
    document.getElementById('confirmPasswordInputEditarUsuario').value = "";
    document.getElementById('passwordInputEditarUsuario').type = "password";
    document.getElementById('confirmPasswordInputEditarUsuario').type = "password";
    document.getElementById('idPerfilUsuarioInput').value = objUser.idPerfilUsuario;
    document.getElementById('editarUsuarioMessageError').classList.remove('shown');

    // Adaptar componente de selección de perfil
    actualizarComponentesSegunTipoUsuario(objUser.email == "admin@dimacof.com")
}

function openModalEditarUsuario(button, usersDB) {
    const fila = button.closest('tr');
    const celdaEmail = fila.getElementsByClassName('email')[0]; 
    const email = celdaEmail.innerText.trim();
    const objUser= returnObjUserByEmail(email, usersDB);
  
    // LLenar campos del formulario de edición de usuario
    fillFieldsEditarUsuario(objUser);

    /*
    // Realizar la consulta al backend, llenar tabla de recompensas y abrir el modal
    getDetalleCanjeByIdCanjeFetch(objCanje['idCanje']);*/

    openModal("modalEditarUsuario");
}

function openModalEliminarUsuario(button, usersDB) {
    const fila = button.closest('tr');
    const celdaEmail = fila.getElementsByClassName('email')[0]; 
    const email = celdaEmail.innerText;
    const objUser = returnObjUserByEmail(email, usersDB);
    const userName = objUser.name;
    
    document.getElementById("idMessageConfirmModal").innerText = `¿Está seguro de eliminar el usuario ${userName}?`

    openConfirmModal('modalConfirmActionEliminarUsuario').then((response) => {
        if (response) {
            console.log(`Eliminando usuario...${userName}`);
            // Recargar la página después de que la solicitud se haya procesado correctamente
            location.reload();
            return;
        }
    });
}

function returnObjUserByEmail(email, usersDB) {
    objUser = usersDB.find(user => user.email === email) || null;
    return objUser;
}
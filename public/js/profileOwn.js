let objUserToEdit = null;

function actualizarComponentesSegunTipoUsuario(isAdminEditModal) {
    const perfilUsuarioInput = document.getElementById('perfilUsuarioInputEditarUsuario'); 
    const perfilUsuarioContainer = document.getElementById('perfilUsuarioSelectEditarUsuario'); 
    const perfilUsuarioSpan = perfilUsuarioContainer.querySelector('span');
    const isAdminLogged = userLoggedMAIN.email == "admin@dimacof.com";

    if (isAdminLogged) {
        if (!isAdminEditModal) {
            //Habilitar el input de perfil
            perfilUsuarioInput.classList.remove('blocked');
            perfilUsuarioInput.removeAttribute('disabled');
            perfilUsuarioInput.classList.add('onlySelectInput', 'long');
            perfilUsuarioInput.classList.add('onlySelectInput', 'long'); 
            perfilUsuarioSpan.classList.remove('blocked');
            perfilUsuarioSpan.removeAttribute('disabled');
            perfilUsuarioContainer.classList.remove('blocked', 'noFocusBorder');
        } else {
            //Deshabilitar el input de perfil
            perfilUsuarioInput.classList.add('onlySelectInput', 'long', 'blocked');
            perfilUsuarioSpan.classList.add('blocked');
            perfilUsuarioContainer.classList.add('noFocusBorder', 'blocked');
            perfilUsuarioInput.setAttribute('disabled', true); 
            perfilUsuarioSpan.setAttribute('disabled', true);
        }
    } else {
        //Deshabilitar el input de perfil
        perfilUsuarioInput.classList.add('onlySelectInput', 'long', 'blocked');
        perfilUsuarioSpan.classList.add('blocked');
        perfilUsuarioContainer.classList.add('noFocusBorder', 'blocked');
        perfilUsuarioInput.setAttribute('disabled', true); 
        perfilUsuarioSpan.setAttribute('disabled', true);
    }
}

function fillFieldsEditarUsuario(objUser) {
    if (!objUser) {
        return;
    }

    const viewIcons = Array.from(document.getElementsByClassName('viewPasswordIcon'));  
    viewIcons.forEach(icon => {icon.textContent = "visibility_off";});

    //consoleLogJSONItems(objUser);

    document.getElementById('idUser').value = objUser.id;
    document.getElementById('nameInputEditarUsuario').value = objUser.name;
    document.getElementById('emailInputEditarUsuario').value = objUser.email;
    document.getElementById('perfilUsuarioInputEditarUsuario').value = objUser.nombre_PerfilUsuario;
    document.getElementById('passwordInputEditarUsuario').value = "";
    document.getElementById('passwordInputEditarUsuario').type = "password";
    document.getElementById('confirmPasswordInputEditarUsuario').value = "";
    document.getElementById('confirmPasswordInputEditarUsuario').type = "password";
    document.getElementById('idPerfilEditarUsuarioInput').value = objUser.idPerfilUsuario;

    // Datos personales
    document.getElementById('DNIInputEditarUsuario').value = objUser.DNI;
    document.getElementById('personalNameInputEditarUsuario').value = objUser.personalName;
    document.getElementById('surnameInputEditarUsuario').value = objUser.surname;
    document.getElementById('fechaNacimientoInputEditarUsuario').value = objUser.fechaNacimiento;
    document.getElementById('correoPersonalInputEditarUsuario').value = objUser.correoPersonal;
    document.getElementById('celularPersonalInputEditarUsuario').value = objUser.celularPersonal;
    document.getElementById('celularCorporativoInputEditarUsuario').value = objUser.celularCorporativo;

    // Mensajes de error de las secciones del modal
    document.getElementById('editarDatosUsuarioMessageError').classList.remove('shown');
    document.getElementById('editarDatosPersonalesMessageError').classList.remove('shown');

    // Adaptar componente de selección de perfil
    actualizarComponentesSegunTipoUsuario(objUser.email == "admin@dimacof.com")

    objUserToEdit = objUser;
}

function openModalEditarUsuario(button, usersDB) {
    const fila = button.closest('tr');
    const celdaEmail = fila.getElementsByClassName('email')[0]; 
    const email = celdaEmail.innerText.trim();
    const objUser = returnObjUserByEmail(email, usersDB);
    
    // LLenar campos del formulario de edición de usuario
    fillFieldsEditarUsuario(objUser);

    openModal("modalEditarUsuario");
}

function openModalHabilitarUsuario(button, usersDB) {
    const fila = button.closest('tr');
    const celdaEmail = fila.getElementsByClassName('email')[0]; 
    const email = celdaEmail.innerText;
    const objUser = returnObjUserByEmail(email, usersDB);
    const userName = objUser.name;
    
    document.getElementById("idMessageConfirmModal").innerText = `¿Está seguro de habilitar al usuario ${userName}?`

    openConfirmModal('modalConfirmActionPerfilUsuario').then((response) => {
        if (response) {
            habilitarUsuario(objUser.id)
            return;
        }
    });
}

async function habilitarUsuario(idUsuario) {
    const url = `${baseUrlMAIN}/dashboard-enableUsuario/${idUsuario}`;

    try {
        // Obtener el token CSRF desde el contenido de la página
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrfToken, 
            }
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(errorText);
        }

        const mensaje = await response.json();

        sessionStorage.setItem('usuarioHabilitado', 'true'); // Manejado en la vista profileOwn.blade.php

        location.reload();
    } catch (error) {
        console.error('Error al habilitar el usuario con id: ' + idUsuario, error.message);
    }
}

function openModalInhabilitarUsuario(button, usersDB) {
    const fila = button.closest('tr');
    const celdaEmail = fila.getElementsByClassName('email')[0]; 
    const email = celdaEmail.innerText;
    const objUser = returnObjUserByEmail(email, usersDB);
    const userName = objUser.name;
    
    document.getElementById("idMessageConfirmModal").innerText = `¿Está seguro de inhabilitar al usuario ${userName}?`

    openConfirmModal('modalConfirmActionPerfilUsuario').then((response) => {
        if (response) {
            inhabilitarUsuario(objUser.id)
            return;
        }
    });
}

async function inhabilitarUsuario(idUsuario) {
    const url = `${baseUrlMAIN}/dashboard-disableUsuario/${idUsuario}`;

    try {
        // Obtener el token CSRF desde el contenido de la página
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const response = await fetch(url, {
            method: 'DELETE', 
            headers: {
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrfToken, 
            }
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(errorText);
        }

        const mensaje = await response.json();

        sessionStorage.setItem('usuarioInhabilitado', 'true'); // Manejado en la vista profileOwn.blade.php

        location.reload();
    } catch (error) {
        console.error('Error al inhabilitar el usuario con id: ' + idUsuario, error.message);
    }
}

function openModalEliminarUsuario(button, usersDB) {
    const fila = button.closest('tr');
    const celdaEmail = fila.getElementsByClassName('email')[0]; 
    const email = celdaEmail.innerText;
    const objUser = returnObjUserByEmail(email, usersDB);
    const userName = objUser.name;
    
    document.getElementById("idMessageConfirmModal").innerText = `¿Está seguro de eliminar al usuario ${userName}?`

    openConfirmModal('modalConfirmActionPerfilUsuario').then((response) => {
        if (response) {
            eliminarUsuario(objUser.id)
            return;
        }
    });
}

async function eliminarUsuario(idUsuario) {
    const url = `${baseUrlMAIN}/dashboard-deleteUsuario/${idUsuario}`;
    //const url = `{{ route('usuarios.delete', ':idUsuario') }}`.replace(':idUsuario', idUsuario);

    try {
        // Obtener el token CSRF desde el contenido de la página
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const response = await fetch(url, {
            method: 'DELETE', 
            headers: {
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrfToken, 
            }
        });

        const mensaje = await response.json();

        if (!response.ok) {
            // Verificar los posibles errores según el código de error
            const messageErrorModal = document.getElementById('messageErrorModalFailedAction')
            switch (mensaje.error_code) {
                case 404:
                    messageErrorModal.textContent = 'El usuario no fue encontrado. Por favor, verifique el ID proporcionado';
                    justOpenModal('errorModalPerfilUsuario');
                    break;
                case 1451:
                    messageErrorModal.textContent = `${mensaje.message}. ${mensaje.details}.`;
                    justOpenModal('errorModalPerfilUsuario');
                    break;
                case 500:
                    messageErrorModal.textContent = 'Ocurrió un error inesperado. Por favor, intente nuevamente más tarde';
                    justOpenModal('errorModalPerfilUsuario');
                    break;
                default:
                    messageErrorModal.textContent = 'Ocurrió un error no identificado.';
                    justOpenModal('errorModalPerfilUsuario');
            }
            return;
        }

        // Eliminar exitosamente
        sessionStorage.setItem('usuarioEliminado', 'true');
        location.reload();
    } catch (error) {
        console.error(`Error de red al eliminar el usuario con id: ${idUsuario}\n`, error);
        alert('No se pudo conectar con el servidor. Por favor, revise su conexión de red.');
    }
}

function returnObjUserByEmail(email, usersDB) {
    objUser = usersDB.find(user => user.email === email) || null;
    return objUser;
}

function closeModalProfileOwn(idModal) {
    closeModal(idModal);

    setTimeout(() => {
        const modal = document.getElementById(idModal);
        if (!modal) return;
        
        const tabs = modal.querySelectorAll(".section-tab");
        const sections = modal.querySelectorAll(".sectionContent");
        
        // Remover 'active' solo dentro del modal correspondiente
        tabs.forEach(t => t.classList.remove("active"));
        sections.forEach(section => section.classList.remove("active"));
        
        // Restaurar la pestaña y sección inicial
        if (tabs.length > 0) tabs[0].classList.add("active");
        if (sections.length > 0) sections[0].classList.add("active");
    }, 500); // Se ejecuta después de 500 ms
}

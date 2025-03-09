let objUserToEdit = null;

document.addEventListener('DOMContentLoaded', function() {
    try {
        const modalOpened = StorageHelper.load('modalOpenedProfileOwn');
        if (modalOpened != null || modalOpened != "") {
            if (modalOpened == "crear") {
                const objCrear = StorageHelper.loadModalDataFromStorage('modalCrearUsuarioObject');
                if (objCrear) {
                    fillFieldsCrearUsuario(objCrear);
                }
                justOpenModal("modalCrearUsuario");
            } else if (modalOpened == "editarDominioCorreo") {
                const objEditarDominioCorreo = StorageHelper.loadModalDataFromStorage('modalEditarDominioCorreoObject');
                if (objEditarDominioCorreo) {
                    document.getElementById('newDomainInputEditarDominioCorreo').value = objEditarDominioCorreo;
                }
                justOpenModal("modalEditarDominioCorreo");
            } else if (modalOpened == "editar") {
                const objEditar = StorageHelper.loadModalDataFromStorage('modalEditarUsuarioObject');
                if (objEditar) {
                    fillFieldsEditarUsuario(objEditar);
                }
                justOpenModal("modalEditarUsuario");
            }  
        }
    } catch (error) {
        console.error( error);
    }
});

// Obtener los inputs del formulario
const inputsCrearUsuarioProfileOWn = {
    name: document.getElementById('nameInputCrearUsuario'),
    email: document.getElementById('emailHiddenInputCrearUsuario'),
    nombre_PerfilUsuario: document.getElementById('perfilUsuarioInputCrearUsuario'),
    idPerfilUsuario: document.getElementById('idPerfilCrearUsuarioInput'),
    DNI: document.getElementById('DNIInputCrearUsuario'),
    personalName: document.getElementById('personalNameInputCrearUsuario'),
    surname: document.getElementById('surnameInputCrearUsuario'),
    fechaNacimiento: document.getElementById('fechaNacimientoInputCrearUsuario'),
    correoPersonal: document.getElementById('correoPersonalInputCrearUsuario'),
    celularPersonal: document.getElementById('celularPersonalInputCrearUsuario'),
    celularCorporativo: document.getElementById('celularCorporativoInputCrearUsuario'),
};

// Guardar la data en sessionStorage cada vez que el usuario escriba
Object.entries(inputsCrearUsuarioProfileOWn).forEach(([key, input]) => {
    if (input) { // Verifica que el input no sea null
        const currentData = StorageHelper.load('modalCrearUsuarioObject') || {};

        input.addEventListener("input", () => {
            currentData[key] = input.value;
            StorageHelper.save('modalCrearUsuarioObject', currentData);
        });
    }
});

function saveStorageDataCrearUsuario() {
    // Cargar datos previos de sessionStorage o inicializar objeto vacío
    const currentData = StorageHelper.load('modalCrearUsuarioObject') || {};

    // Cargar el valor guardado de currentData si existe
    if (currentData != {}) {
        fillFieldsCrearUsuario(currentData);
    }

    // Recorrer los inputs para guardar valores iniciales
    Object.entries(inputsCrearUsuarioProfileOWn).forEach(([key, input]) => {
        if (inputsCrearUsuarioProfileOWn) {
            // Guardar valor inicial
            currentData[key] = input.value;
        }
    });

    // Guardar los valores iniciales en sessionStorage
    StorageHelper.save('modalCrearUsuarioObject', currentData);
}

function fillFieldsCrearUsuario(objUser) {
    if (!objUser) {
        return;
    }

    const viewIcons = Array.from(document.getElementsByClassName('viewPasswordIcon'));  
    viewIcons.forEach(icon => {icon.textContent = "visibility_off";});

    // consoleLogJSONItems(objUser);

    // Datos de usuario
    document.getElementById('nameInputCrearUsuario').value = objUser.name;
    document.getElementById('emailTextInputCrearUsuario').value = objUser.email.split('@')[0];
    document.getElementById('emailHiddenInputCrearUsuario').value = objUser.email;
    document.getElementById('perfilUsuarioInputCrearUsuario').value = objUser.nombre_PerfilUsuario;
    document.getElementById('passwordInputCrearUsuario').value = "";
    document.getElementById('passwordInputCrearUsuario').type = "password";
    document.getElementById('confirmPasswordInputCrearUsuario').value = "";
    document.getElementById('confirmPasswordInputCrearUsuario').type = "password";
    document.getElementById('idPerfilCrearUsuarioInput').value = objUser.idPerfilUsuario;
    
    // Datos personales
    document.getElementById('DNIInputCrearUsuario').value = objUser.DNI;
    document.getElementById('personalNameInputCrearUsuario').value = objUser.personalName;
    document.getElementById('surnameInputCrearUsuario').value = objUser.surname;
    document.getElementById('fechaNacimientoInputCrearUsuario').value = objUser.fechaNacimiento;
    document.getElementById('correoPersonalInputCrearUsuario').value = objUser.correoPersonal;
    document.getElementById('celularPersonalInputCrearUsuario').value = objUser.celularPersonal;
    document.getElementById('celularCorporativoInputCrearUsuario').value = objUser.celularCorporativo;

    // Mensajes de error de las secciones del modal
    document.getElementById('crearDatosUsuarioMessageError').classList.remove('shown');
    document.getElementById('crearDatosPersonalesMessageError').classList.remove('shown');
}

function openModalCrearUsuario(id) {
    // Guardar en sessionStorage que el modal se ha abierto
    StorageHelper.save('modalOpenedProfileOwn', 'crear');

    saveStorageDataCrearUsuario();

    // Abrir el modal
    justOpenModal(id);
}

var newDomainProfileOWn = document.getElementById('newDomainInputEditarDominioCorreo');

// Guardar el valor en sessionStorage cada vez que el usuario escriba
newDomainProfileOWn.addEventListener("input", () => {
    StorageHelper.save('modalEditarDominioCorreoObject', newDomainProfileOWn.value);
    console.log("newDomain guardado: " + newDomainProfileOWn.value);
});

function saveStorageDataEditarDominioCorreo() {
    // Obtener el input del modal
    if (newDomainProfileOWn) {
        // Cargar el valor guardado en sessionStorage si existe
        const savedDomain = StorageHelper.load('modalEditarDominioCorreoObject') || "";
        if (savedDomain != "") {
            newDomainProfileOWn.value = savedDomain;
        }

        // Guardar el valor inicial (sin modificar el input)
        StorageHelper.save('modalEditarDominioCorreoObject', newDomainProfileOWn.value);
        console.log("newDomain guardado: " + newDomainProfileOWn.value);
    } else {
        console.warn("El input 'newDomainInputEditarDominioCorreo' no se encontró en el DOM.");
    }
}

function openModalEditarDominio(id) {
    // Guardar en sessionStorage que el modal se ha abierto
    StorageHelper.save('modalOpenedProfileOwn', 'editarDominioCorreo');

    saveStorageDataEditarDominioCorreo();

    // Abrir el modal
    justOpenModal(id);
}

function actualizarComponentesSegunTipoUsuarioEditar(isAdminEditModal) {
    const emailTextInput = document.getElementById('emailTextInputEditarUsuario'); 
    const emailTextInputContainer = document.getElementById('idEmailTextInputContainer'); 
    const perfilUsuarioLabel = document.getElementById('perfilUsuarioEditarLabel');
    const perfilUsuarioInput = document.getElementById('perfilUsuarioInputEditarUsuario'); 
    const perfilUsuarioContainer = document.getElementById('perfilUsuarioSelectEditarUsuario'); 
    const perfilUsuarioSpan = perfilUsuarioContainer.querySelector('span');
    const isAdminLogged = (userLoggedMAIN.email == adminEmailMAIN);

    if (isAdminLogged && !isAdminEditModal) {
        //Habilitar el input de emailText y perfil
        emailTextInput.classList.remove('blocked');
        emailTextInput.removeAttribute('disabled', true); 
        emailTextInputContainer.classList.remove('noFocusBorder', 'blocked');
        perfilUsuarioLabel.classList.remove('secondary-label');
        perfilUsuarioLabel.classList.add('primary-label');
        perfilUsuarioInput.classList.remove('blocked');
        perfilUsuarioInput.removeAttribute('disabled');
        perfilUsuarioInput.classList.add('onlySelectInput', 'long');
        perfilUsuarioInput.classList.add('onlySelectInput', 'long'); 
        perfilUsuarioSpan.classList.remove('blocked');
        perfilUsuarioSpan.removeAttribute('disabled');
        perfilUsuarioContainer.classList.remove('noFocusBorder', 'blocked');
    } else {
        //Deshabilitar el input de emailText y perfil
        emailTextInput.classList.add('blocked');
        emailTextInput.setAttribute('disabled', true); 
        emailTextInputContainer.classList.add('noFocusBorder', 'blocked');
        perfilUsuarioLabel.classList.remove('primary-label');
        perfilUsuarioLabel.classList.add('secondary-label');
        perfilUsuarioInput.classList.add('onlySelectInput', 'long', 'blocked');
        perfilUsuarioSpan.classList.add('blocked');
        perfilUsuarioContainer.classList.add('noFocusBorder', 'blocked');
        perfilUsuarioInput.setAttribute('disabled', true); 
        perfilUsuarioSpan.setAttribute('disabled', true);
    }
    
    // Construir el JSON
    //const modalEditarUsuarioObject = "";

    // Guardar en session storage
    //StorageHelper.saveModalDataToStorage('modalEditarUsuarioObject', modalEditarUsuarioObject);
}

function fillFieldsEditarUsuario(objUser) {
    if (!objUser) {
        return;
    }

    const checkInterval = setInterval(() => {
        if (userLoggedMAIN && adminEmailMAIN) {
            clearInterval(checkInterval);

            const viewIcons = Array.from(document.getElementsByClassName('viewPasswordIcon'));  
            viewIcons.forEach(icon => {icon.textContent = "visibility_off";});

            //consoleLogJSONItems(objUser);

            document.getElementById('idUser').value = objUser.id;
            document.getElementById('nameInputEditarUsuario').value = objUser.name;
            document.getElementById('emailTextInputEditarUsuario').value = objUser.email.split('@')[0];
            document.getElementById('emailHiddenInputEditarUsuario').value = objUser.email;
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
            actualizarComponentesSegunTipoUsuarioEditar(objUser.email == adminEmailMAIN)

            objUserToEdit = objUser;
        }
    }, 50);

    setTimeout(() => {
        clearInterval(checkInterval);
        if (!userLoggedMAIN || !adminEmailMAIN) {
            console.warn("Tiempo de espera agotado, userLoggedMAIN o adminEmailMAIN no están definidos.");
        }
    }, 1000);
}

function openModalEditarUsuario(button, usersDB) {
    const fila = button.closest('tr');
    const celdaEmail = fila.getElementsByClassName('email')[0]; 
    const email = celdaEmail.innerText.trim();
    const objUser = returnObjUserByEmail(email, usersDB);
    
    // LLenar campos del formulario de edición de usuario
    fillFieldsEditarUsuario(objUser);

    // Guardar en session storage
    StorageHelper.save('modalOpenedProfileOwn', 'editar');
    StorageHelper.saveModalDataToStorage('modalEditarUsuarioObject', objUser);

    justOpenModal("modalEditarUsuario");
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
    // Limpiar storage
    StorageHelper.clear('modalOpenedProfileOwn');

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

function fillHiddenEmailInput(firstInput, secondInputID) {
    const input = firstInput;
    const secInput = document.getElementById(secondInputID);

    if (input && secInput) {
        secInput.value = input.value + '@' + emailDomainMAIN;

        if (firstInput.id == "emailTextInputCrearUsuario") {
            // Guardar en session storage
            const currentData = StorageHelper.load('modalCrearUsuarioObject') || {};
            currentData['email'] = secInput.value;

            StorageHelper.save('modalCrearUsuarioObject', currentData);
        }
    }
}

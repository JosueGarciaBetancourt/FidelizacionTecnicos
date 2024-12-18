let nameInputCrearUsuario = document.getElementById('nameInputCrearUsuario');
let emailInputCrearUsuario = document.getElementById('emailInputCrearUsuario');
let passwordInputCrearUsuario = document.getElementById('passwordInputCrearUsuario');
let confirmPasswordInputCrearUsuario = document.getElementById('confirmPasswordInputCrearUsuario');
let perfilUsuarioInputCrearUsuario = document.getElementById('perfilUsuarioInputCrearUsuario');
let crearUsuarioMessageError = document.getElementById('crearUsuarioMessageError');
let confirmPasswordTooltipCrearUsuario = document.getElementById('idConfirmPasswordTooltipCrear');
let emailTooltipCrear = document.getElementById('idEmailTooltipCrear');
let idPerfilUsuarioInputCrear = document.getElementById('idPerfilUsuarioInputCrear');

let formCrearUsuarioInputsArray = [
	nameInputCrearUsuario,
    emailInputCrearUsuario,
    passwordInputCrearUsuario,
    confirmPasswordInputCrearUsuario,
    perfilUsuarioInputCrearUsuario,
    idPerfilUsuarioInputCrear,
];

function returnIdByNombrePerfilUser(nombrePerfil, perfilesDB) {
    if (typeof perfilesDB !== 'object' || perfilesDB === null) {
        console.error('perfilesDB no es un objeto válido');
        return null;
    }
    
    for (const [id, nombre] of Object.entries(perfilesDB)) {
        if (nombre === nombrePerfil) {
            return id;
        }
    }
    return null; 
}

function selectOptionPerfilUsuario(value, idInput, idOptions, perfilesDB) {
    var input = document.getElementById(idInput);
    var options = document.getElementById(idOptions);
    
    if (input) {
        input.value = value;
        options.classList.remove('show'); // Ocultar las opciones
    } else {
        console.error('El elemento con id ' + idOptions + ' no se encontró en el DOM');
    }

    const idPerfilUsuario = returnIdByNombrePerfilUser(value, perfilesDB);
    console.log(idPerfilUsuario);
    idPerfilUsuarioInputCrear.value = idPerfilUsuario;
}

function validarCamposVaciosFormularioCrearUsuario() {
    var allFilled = true;
    formCrearUsuarioInputsArray.forEach(input => {
        if (!input.value.trim()) {
            allFilled = false;
        }
    });
    return allFilled;
}

function validarCamposCorrectosFormularioTecnicoCrear() {
    if (confirmPasswordInputCrearUsuario.value.trim() !== passwordInputCrearUsuario.value.trim()) {
        showHideTooltip(confirmPasswordTooltipCrearUsuario, "La confirmación de contraseña no coincide");
        return false
    }

    if (passwordInputCrearUsuario.value.length <= 6 && passwordInputCrearUsuario.value.length > 0) {
        crearUsuarioMessageError.textContent = "La contraseña debe contener más de 6 caracteres.";
        crearUsuarioMessageError.classList.add("shown");
        return false
    }

    // Validar email
    const emailInput = emailInputCrearUsuario.value;
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (!emailPattern.test(emailInput)) {
        showHideTooltip(emailTooltipCrear, "Por favor, introduce un correo electrónico válido con un dominio.");
        return false
    }

    if (emailInput !== emailInput.toLowerCase()) {
        showHideTooltip(emailTooltipCrear, "Por favor, introduce un correo electrónico válido en minúsculas.");
        return false
    }
    
    return true;
}

function guardarModalCrearUsuario(idModal, idForm) {
    if (validarCamposVaciosFormularioCrearUsuario()) {
        if (!validarCamposCorrectosFormularioTecnicoCrear()) {
            return;
        }
        crearUsuarioMessageError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        crearUsuarioMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        crearUsuarioMessageError.classList.add("shown");
      }
}
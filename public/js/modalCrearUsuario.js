let nameInputCrearUsuario = document.getElementById('nameInputCrearUsuario');
let emailInputCrearUsuario = document.getElementById('emailInputCrearUsuario');
let passwordInputCrearUsuario = document.getElementById('passwordInputCrearUsuario');
let confirmPasswordInputCrearUsuario = document.getElementById('confirmPasswordInputCrearUsuario');
let perfilUsuarioInputCrearUsuario = document.getElementById('perfilUsuarioInputCrearUsuario');
let crearUsuarioMessageError = document.getElementById('crearUsuarioMessageError');
let confirmPasswordTooltip = document.getElementById('idConfirmPasswordTooltipCrear');
let idPerfilUsuarioInputCrear = document.getElementById('idPerfilUsuarioInputCrear');

let formEditUsuarioInputsArray = [
	nameInputCrearUsuario,
    emailInputCrearUsuario,
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

function validarCamposVaciosFormularioEditUsuario() {
  var allFilled = true;
  formEditUsuarioInputsArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function validarCamposCorrectosFormularioTecnicoEdit() {
    if (confirmPasswordInputCrearUsuario.value.trim() !== passwordInputCrearUsuario.value.trim()) {
        showHideTooltip(confirmPasswordTooltip, "La confirmación de contraseña no coincide");
        return false
    }

    if (passwordInputCrearUsuario.value.length <= 6 && passwordInputCrearUsuario.value.length > 0) {
        crearUsuarioMessageError.textContent = "La contraseña debe contener más de 6 caracteres.";
        crearUsuarioMessageError.classList.add("shown");
        return false
    }
    
    return true;
}

function guardarModalCrearUsuario(idModal, idForm) {
    if (validarCamposVaciosFormularioEditUsuario()) {
        if (!validarCamposCorrectosFormularioTecnicoEdit()) {
            return;
        }
        crearUsuarioMessageError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        crearUsuarioMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        crearUsuarioMessageError.classList.add("shown");
      }
}
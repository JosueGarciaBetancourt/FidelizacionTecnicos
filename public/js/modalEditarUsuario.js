let nameInputEditarUsuario = document.getElementById('nameInputEditarUsuario');
let emailInputEditarUsuario = document.getElementById('emailInputEditarUsuario');
let passwordInputEditarUsuario = document.getElementById('passwordInputEditarUsuario');
let confirmPasswordInputEditarUsuario = document.getElementById('confirmPasswordInputEditarUsuario');
let perfilUsuarioInputEditarUsuario = document.getElementById('perfilUsuarioInputEditarUsuario');
let editarUsuarioMessageError = document.getElementById('editarUsuarioMessageError');
let confirmPasswordTooltip = document.getElementById('idConfirmPasswordTooltip');
let idPerfilUsuarioInput = document.getElementById('idPerfilUsuarioInput');

let formEditUsuarioInputsArray = [
	nameInputEditarUsuario,
    emailInputEditarUsuario,
    perfilUsuarioInputEditarUsuario,
    idPerfilUsuarioInput,
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

function selectOptionPerfilUsuarioEdit(value, idInput, idOptions, perfilesDB) {
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
    idPerfilUsuarioInput.value = idPerfilUsuario;
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
    if (confirmPasswordInputEditarUsuario.value.trim() !== passwordInputEditarUsuario.value.trim()) {
        showHideTooltip(confirmPasswordTooltip, "La confirmación de contraseña no coincide");
        return false
    }

    if (passwordInputEditarUsuario.value.length <= 6 && passwordInputEditarUsuario.value.length > 0) {
        editarUsuarioMessageError.textContent = "La contraseña debe contener más de 6 caracteres.";
        editarUsuarioMessageError.classList.add("shown");
        return false
    }
    
    return true;
}

function guardarModalEditarUsuario(idModal, idForm) {
    if (validarCamposVaciosFormularioEditUsuario()) {
        if (!validarCamposCorrectosFormularioTecnicoEdit()) {
            return;
        }
        editarUsuarioMessageError.classList.remove("shown");
        guardarModal(idModal, idForm);	
    } else {
        editarUsuarioMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        editarUsuarioMessageError.classList.add("shown");
      }
}

document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".section-tab.editar");
    const sections = document.querySelectorAll(".sectionContent.editar");
    
    tabs.forEach((tab, index) => {
        tab.addEventListener("click", function (event) {
            event.preventDefault();
            
            // Remover la clase 'active' de todas las pestañas y secciones
            tabs.forEach(t => t.classList.remove("active"));
            sections.forEach(section => section.classList.remove("active"));
            
            // Agregar la clase 'active' a la pestaña y sección correspondiente
            tab.classList.add("active");
            sections[index].classList.add("active");
        });
    });
});
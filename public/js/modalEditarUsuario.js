let nameInputEditarUsuario = document.getElementById('nameInputEditarUsuario');
let emailTextInputEditarUsuario = document.getElementById('emailTextInputEditarUsuario');
let emailHiddenInputEditarUsuario = document.getElementById('emailHiddenInputEditarUsuario');
let passwordInputEditarUsuario = document.getElementById('passwordInputEditarUsuario');
let confirmPasswordInputEditarUsuario = document.getElementById('confirmPasswordInputEditarUsuario');
let perfilUsuarioInputEditarUsuario = document.getElementById('perfilUsuarioInputEditarUsuario');
let editarDatosUsuarioMessageError = document.getElementById('editarDatosUsuarioMessageError');
let confirmPasswordTooltip = document.getElementById('idConfirmPasswordTooltip');
let idPerfilEditarUsuarioInput = document.getElementById('idPerfilEditarUsuarioInput');

let DNIEditarUsuarioInput = document.getElementById('DNIInputEditarUsuario');
let personalNameEditarUsuarioInput = document.getElementById('personalNameInputEditarUsuario');
let surnameEditarUsuarioInput = document.getElementById('surnameInputEditarUsuario');
let fechaNacimientoEditarUsuarioInput = document.getElementById('fechaNacimientoInputEditarUsuario');
let dateMessageEditarUsuarioError = document.getElementById('dateMessageEditarUsuarioError');
let correoPersonalEditarUsuarioInput = document.getElementById('correoPersonalInputEditarUsuario');
let correoPersonalEditarUsuarioTooltip = document.getElementById('correoPersonalTooltipEditarUsuario');
let celularPersonalEditarUsuarioInput = document.getElementById('celularPersonalInputEditarUsuario');
let celularCorporativoEditarUsuarioInput= document.getElementById('celularCorporativoInputEditarUsuario');
let mayorDeEdadEditarUsuario = false;
let editarDatosPersonalesMessageError = document.getElementById('editarDatosPersonalesMessageError');

let formEditUsuarioRequiredInputsArray = [
	nameInputEditarUsuario,
    emailTextInputEditarUsuario,
    perfilUsuarioInputEditarUsuario,
    idPerfilEditarUsuarioInput,
];

document.addEventListener("DOMContentLoaded", function() {
    if (fechaNacimientoEditarUsuarioInput) {
        // Establecer los atributos min y max una sola vez (evitar que se seleccionen fechas fuera del os límites)
        fechaNacimientoEditarUsuarioInput.setAttribute('min', minDateMAIN);
        fechaNacimientoEditarUsuarioInput.setAttribute('max', maxDateMAIN);
    
        fechaNacimientoEditarUsuarioInput.addEventListener('input', function() {
            validateRealTimeDateEditarUsuario();
        });
    }
});

// Función para validar la fecha
function validateRealTimeDateEditarUsuario() {
    const selectedDate = fechaNacimientoEditarUsuarioInput.value;
    const objSelectedDate = new Date(selectedDate);

    // Verificar si el campo de fecha está vacío
    if (!selectedDate) {
        dateMessageEditarUsuarioError.classList.remove('shown'); 
        return; // Salir de la función si el campo está vacío
    }

    if (selectedDate < minDateMAIN) {
        dateMessageEditarUsuarioError.textContent = `La fecha debe ser posterior al 1 de enero de ${minYearMAIN}.`; 
        dateMessageEditarUsuarioError.classList.add('shown'); // Mostrar mensaje de error
        return;
    }

    if (selectedDate >= maxDateMAIN) {
        dateMessageEditarUsuarioError.textContent = 'La fecha debe ser anterior a la fecha actual'; 
        dateMessageEditarUsuarioError.classList.add('shown'); // Mostrar mensaje de error
        return;
    }
    
    // Calcula la diferencia en milisegundos
    const differenceInMilliseconds = objMaxDateMAIN - objSelectedDate;
    
    // Calcula los años a partir de la diferencia en milisegundos
    const millisecondsPerYear = 1000 * 60 * 60 * 24 * 365.25; // Considera los años bisiestos
    const edad = Math.floor(differenceInMilliseconds / millisecondsPerYear);

    // Verificar si es mayor de edad
    if (edad < 18) {
        dateMessageEditarUsuarioError.textContent = 'El usuario debe ser mayor de edad.'; 
        dateMessageEditarUsuarioError.classList.add('shown'); 
        mayorDeEdadEditarUsuario = false;
        return false;
    }

    dateMessageEditarUsuarioError.classList.remove('shown');
    mayorDeEdadEditarUsuario = true;
    return true;
}

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
    idPerfilEditarUsuarioInput.value = idPerfilUsuario;
}

function validarCamposVaciosFormularioEditarUsuario() {
  var allFilled = true;
  formEditUsuarioRequiredInputsArray.forEach(input => {
      if (!input.value.trim()) {
          allFilled = false;
      }
  });
  return allFilled;
}

function validarCamposCorrectosFormularioEditarUsuario() {
    let errores = []; // Array para almacenar los errores
    const emailTextPattern = /^[a-z0-9._]+(\+[a-z0-9]+)?$/;
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    // Validar datos de usuario
    if (!emailTextPattern.test(emailTextInputEditarUsuario.value)) {
        if (emailTextInputEditarUsuario.closest(".sectionContent.crear.active")) {
            showHideTooltip(emailTooltipCrear, "Por favor, introduce un correo electrónico válido");
        }
        errores.push("Correo electrónico no válido.");
    }

    if (emailTextInputEditarUsuario.value !== emailTextInputEditarUsuario.value.toLowerCase()) {
        if (emailTextInputEditarUsuario.closest(".sectionContent.crear.active")) {
            showHideTooltip(emailTooltipCrear, "Por favor, introduce un correo electrónico válido en minúsculas");
        }
        errores.push("Correo electrónico no válido en mayúsculas.");
    }

    if (confirmPasswordInputEditarUsuario.value.trim() !== passwordInputEditarUsuario.value.trim()) {
        errores.push("La confirmación de contraseña no coincide.");
    }

    if (passwordInputEditarUsuario.value.length <= 6 && passwordInputEditarUsuario.value.length > 0) {
        editarDatosUsuarioMessageError.textContent = "La contraseña debe contener más de 6 caracteres.";
        editarDatosUsuarioMessageError.classList.add("shown");
        errores.push("La contraseña debe contener más de 6 caracteres.");
    }

    // Validar datos personales (opcionales)
    if (DNIEditarUsuarioInput.value && DNIEditarUsuarioInput.value.trim() !== "") {
        const isDniValid = validateInputLength(DNIEditarUsuarioInput, 8);
        if (!isDniValid) errores.push("DNI no válido.");
    }

    if (fechaNacimientoEditarUsuarioInput.value || fechaNacimientoEditarUsuarioInput.value.trim() !== "") {
        if (!validateRealTimeDateEditarUsuario()) errores.push("Fecha de nacimiento no válida.");
    }

    if (correoPersonalEditarUsuarioInput.value && correoPersonalEditarUsuarioInput.value.trim() !== "") {
        if (!emailPattern.test(correoPersonalEditarUsuarioInput.value)) {
            if (correoPersonalEditarUsuarioInput.closest(".sectionContent.crear.active")) {
                showHideTooltip(correoPersonalCrearUsuarioTooltip, "Por favor, introduce un correo electrónico personal válido");
            }
            errores.push("Correo electrónico personal no válido.");
        } else if (correoPersonalEditarUsuarioInput.value !== correoPersonalEditarUsuarioInput.value.toLowerCase()) {
            if (correoPersonalEditarUsuarioInput.closest(".sectionContent.crear.active")) {
                showHideTooltip(correoPersonalCrearUsuarioTooltip, "Por favor, introduce un correo electrónico personal válido en minúsculas");
            }
            errores.push("Correo electrónico personal no válido en mayúsculas.");
        }
    }

    if (celularPersonalEditarUsuarioInput.value && celularPersonalEditarUsuarioInput.value.trim() !== "") {
        if (!validateInputLength(celularPersonalEditarUsuarioInput, 9)) errores.push("Celular personal no válido.");
    }

    if (celularCorporativoEditarUsuarioInput.value && celularCorporativoEditarUsuarioInput.value.trim() !== "") {
        if (!validateInputLength(celularCorporativoEditarUsuarioInput, 9)) errores.push("Celular corporativo no válido.");
    }

    if (errores.length > 0) {
        editarDatosUsuarioMessageError.textContent = errores.join(" ");
        editarDatosPersonalesMessageError.textContent = errores.join(" ");
        return false;
    }

    editarDatosUsuarioMessageError.textContent = "";
    editarDatosPersonalesMessageError.textContent = "";
    return true;
}

async function guardarModalEditarUsuario(idModal, idForm) {
    if (validarCamposVaciosFormularioEditarUsuario()) {
        if (!validarCamposCorrectosFormularioEditarUsuario()) {
            editarDatosUsuarioMessageError.classList.add("shown");
            editarDatosPersonalesMessageError.classList.add("shown");
            return;
        }
        
        //console.log("id Usuario a editar: " + objUserToEdit.id);

        // Validar duplicados en BD
        const url = `${baseUrlMAIN}/verificar-userEditDataDuplication`;
        const userID = objUserToEdit.id;
        const userEmail = emailHiddenInputEditarUsuario.value.trim();
        const userDNI = DNIEditarUsuarioInput.value.trim();
        const userPersonalEmail = correoPersonalEditarUsuarioInput.value.trim();
        const userPersonalPhone = celularPersonalEditarUsuarioInput.value.trim();

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfTokenMAIN
                },
                body: JSON.stringify({userID: userID, userEmail: userEmail, userDNI: userDNI, userPersonalEmail: userPersonalEmail, userPersonalPhone: userPersonalPhone}),
            });

            if (!response.ok) {
                const errorDetails = await response.text();
                throw new Error(`Error en la solicitud: ${response.status} - ${response.statusText}. Detalles: ${errorDetails}`);
            }
            
            const data = await response.json();

            if (data.success) {
                if (data.duplicates && Object.keys(data.duplicates).length > 0) {
                    let erroresMsg = [];
                
                    if (data.duplicates["userEmail"]) erroresMsg.push(`El correo ${userEmail} ya ha sido registrado.`);
                    if (data.duplicates["userDNI"]) erroresMsg.push(`El DNI ${userDNI} ya ha sido registrado.`);
                    if (data.duplicates["userPersonalEmail"]) erroresMsg.push(`El correo personal ${userPersonalEmail} ya ha sido registrado.`);
                    if (data.duplicates["userPersonalPhone"]) erroresMsg.push(`El celular personal ${userPersonalPhone} ya ha sido registrado.`);
                
                    editarDatosUsuarioMessageError.textContent = erroresMsg.join(" ");
                    editarDatosPersonalesMessageError.textContent = erroresMsg.join(" ");
                    editarDatosUsuarioMessageError.classList.add("shown");
                    editarDatosPersonalesMessageError.classList.add("shown");
                    return;
                }

                editarDatosUsuarioMessageError.classList.remove("shown");
                editarDatosPersonalesMessageError.classList.remove("shown");
               
                const loadingModal = document.getElementById('loadingModal');
                loadingModal.classList.add('show');

                // Limpiar storage
                StorageHelper.clear('modalOpenedProfileOwn');
                guardarModal(idModal, idForm);	
            }
        } catch (error) {
            console.log(error);
            editarDatosUsuarioMessageError.textContent = 'Ocurrió un error al verificar datos duplicados del usuario a editar. Por favor, inténtelo de nuevo.';
            editarDatosPersonalesMessageError.textContent = 'Ocurrió un error al verificar datos duplicados del usuario a editar. Por favor, inténtelo de nuevo.';
            editarDatosUsuarioMessageError.classList.add("shown");
            editarDatosPersonalesMessageError.classList.add("shown");
        }
    } else {
        editarDatosUsuarioMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente.";
        editarDatosPersonalesMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente";
        editarDatosUsuarioMessageError.classList.add("shown");
        editarDatosPersonalesMessageError.classList.add("shown");
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
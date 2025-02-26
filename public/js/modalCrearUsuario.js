let nameInputCrearUsuario = document.getElementById('nameInputCrearUsuario');
let emailInputCrearUsuario = document.getElementById('emailInputCrearUsuario');
let passwordInputCrearUsuario = document.getElementById('passwordInputCrearUsuario');
let confirmPasswordInputCrearUsuario = document.getElementById('confirmPasswordInputCrearUsuario');
let perfilUsuarioInputCrearUsuario = document.getElementById('perfilUsuarioInputCrearUsuario');
let crearDatosUsuarioMessageError = document.getElementById('crearDatosUsuarioMessageError');
let emailTooltipCrear = document.getElementById('correoInputCrearUsuario');
let idPerfilUsuarioInputCrear = document.getElementById('idPerfilUsuarioInputCrear');

let DNIInput = document.getElementById('DNIInputCrearUsuario');
let personalNameInput = document.getElementById('personalNameInputCrearUsuario');
let surnameInput = document.getElementById('surnameInputCrearUsuario');
let fechaNacimientoCrearUsuarioInput = document.getElementById('fechaNacimientoInputCrearUsuario');
let dateMessageCrearUsuarioError = document.getElementById('dateMessageCrearUsuarioError');
let correoPersonalInput = document.getElementById('correoPersonalInputCrearUsuario');
let correoPersonalTooltip = document.getElementById('correoPersonalTooltipCrearUsuario');
let celularPersonalInput = document.getElementById('celularPersonalInputCrearUsuario');
let celularCorporativoInput = document.getElementById('celularCorporativoInputCrearUsuario');
let mayorDeEdadCrearUsuario = false;
let crearDatosPersonalesMessageError = document.getElementById('crearDatosPersonalesMessageError');

let formCrearUsuarioInputsArray = [
	nameInputCrearUsuario,
    emailInputCrearUsuario,
    passwordInputCrearUsuario,
    confirmPasswordInputCrearUsuario,
    perfilUsuarioInputCrearUsuario,
    idPerfilUsuarioInputCrear,
];

document.addEventListener("DOMContentLoaded", function() {
    if (fechaNacimientoCrearUsuarioInput) {
        // Establecer los atributos min y max una sola vez (evitar que se seleccionen fechas fuera del os límites)
        fechaNacimientoCrearUsuarioInput.setAttribute('min', minDateMAIN);
        fechaNacimientoCrearUsuarioInput.setAttribute('max', maxDateMAIN);
    
        fechaNacimientoCrearUsuarioInput.addEventListener('input', function() {
            validateRealTimeDateCrearUsuario();
        });
    }
    
    // Función para validar la fecha
    function validateRealTimeDateCrearUsuario() {
        const selectedDate = fechaNacimientoCrearUsuarioInput.value;
        const objSelectedDate = new Date(selectedDate);
    
        // Verificar si el campo de fecha está vacío
        if (!selectedDate) {
            dateMessageCrearUsuarioError.classList.remove('shown'); 
            return; // Salir de la función si el campo está vacío
        }
    
        if (selectedDate < minDateMAIN) {
            dateMessageCrearUsuarioError.textContent = `La fecha debe ser posterior al 1 de enero de ${minYearMAIN}.`; 
            dateMessageCrearUsuarioError.classList.add('shown'); // Mostrar mensaje de error
            return;
        }
    
        if (selectedDate >= maxDateMAIN) {
            dateMessageCrearUsuarioError.textContent = 'La fecha debe ser anterior a la fecha actual'; 
            dateMessageCrearUsuarioError.classList.add('shown'); // Mostrar mensaje de error
            return;
        }
        
        // Calcula la diferencia en milisegundos
        const differenceInMilliseconds = objMaxDateMAIN - objSelectedDate;
        
        // Calcula los años a partir de la diferencia en milisegundos
        const millisecondsPerYear = 1000 * 60 * 60 * 24 * 365.25; // Considera los años bisiestos
        const edad = Math.floor(differenceInMilliseconds / millisecondsPerYear);
    
        // Verificar si es mayor de edad
        if (edad < 18) {
            dateMessageCrearUsuarioError.textContent = 'El usuario debe ser mayor de edad.'; 
            dateMessageCrearUsuarioError.classList.add('shown'); 
            mayorDeEdadCrearUsuario = false;
            return;
        }
    
        dateMessageCrearUsuarioError.classList.remove('shown');
        mayorDeEdadCrearUsuario = true;
    }
});

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

function selectOptionPerfilUsuarioCrear(value, idInput, idOptions, perfilesDB) {
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
    let errores = []; // Array para almacenar los errores
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    // Validar datos de usuario
    if (!emailPattern.test(emailInputCrearUsuario.value)) {
        if (emailInputCrearUsuario.closest(".sectionContent.crear.active")) {
            showHideTooltip(emailTooltipCrear, "Por favor, introduce un correo electrónico válido con un dominio");
        }
        errores.push("Correo electrónico sin dominio válido.");
    }

    if (emailInputCrearUsuario.value !== emailInputCrearUsuario.value.toLowerCase()) {
        if (emailInputCrearUsuario.closest(".sectionContent.crear.active")) {
            showHideTooltip(emailTooltipCrear, "Por favor, introduce un correo electrónico válido en minúsculas");
        }
        errores.push("Correo electrónico no válido en mayúsculas.");
    }

    if (confirmPasswordInputCrearUsuario.value.trim() !== passwordInputCrearUsuario.value.trim()) {
        errores.push("La confirmación de contraseña no coincide.");
    }

    if (passwordInputCrearUsuario.value.length <= 6 && passwordInputCrearUsuario.value.length > 0) {
        crearDatosUsuarioMessageError.textContent = "La contraseña debe contener más de 6 caracteres.";
        crearDatosUsuarioMessageError.classList.add("shown");
        errores.push("La contraseña debe contener más de 6 caracteres.");
    }

    // Validar datos personales (opcionales)
    if (DNIInput.value && DNIInput.value.trim() !== "") {
        const isDniValid = validateInputLength(DNIInput, 8);
        if (!isDniValid) errores.push("DNI no válido.");
    }

    if (fechaNacimientoCrearUsuarioInput.value || fechaNacimientoCrearUsuarioInput.value.trim() !== "") {
        if (!mayorDeEdadCrearUsuario) errores.push("Fecha de nacimiento no válida.");
    }

    if (correoPersonalInput.value && correoPersonalInput.value.trim() !== "") {
        if (!emailPattern.test(correoPersonalInput.value)) {
            if (correoPersonalInput.closest(".sectionContent.crear.active")) {
                showHideTooltip(correoPersonalTooltip, "Por favor, introduce un correo electrónico personal válido con un dominio");
            }
            errores.push("Correo electrónico personal sin dominio válido.");
        } else if (correoPersonalInput.value !== correoPersonalInput.value.toLowerCase()) {
            if (correoPersonalInput.closest(".sectionContent.crear.active")) {
                showHideTooltip(correoPersonalTooltip, "Por favor, introduce un correo electrónico personal válido en minúsculas");
            }
            errores.push("Correo electrónico personal no válido en mayúsculas.");
        }
    }

    if (celularPersonalInput.value && celularPersonalInput.value.trim() !== "") {
        if (!validateInputLength(celularPersonalInput, 9)) errores.push("Celular personal no válido.");
    }

    if (celularCorporativoInput.value && celularCorporativoInput.value.trim() !== "") {
        if (!validateInputLength(celularCorporativoInput, 9)) errores.push("Celular corporativo no válido.");
    }

    if (errores.length > 0) {
        crearDatosUsuarioMessageError.textContent = errores.join(" ");
        crearDatosPersonalesMessageError.textContent = errores.join(" ");
        return false;
    }

    crearDatosUsuarioMessageError.textContent = "";
    crearDatosPersonalesMessageError.textContent = "";
    return true;
}

async function guardarModalCrearUsuario(idModal, idForm) {
    if (validarCamposVaciosFormularioCrearUsuario()) {
        if (!validarCamposCorrectosFormularioTecnicoCrear()) {
            crearDatosUsuarioMessageError.classList.add("shown");
            crearDatosPersonalesMessageError.classList.add("shown");
            return;
        }

        // Validar duplicados en BD
        const url = `${baseUrlMAIN}/verificar-userDataDuplication`;
        const userEmail = emailInputCrearUsuario.value.trim();
        const userDNI = DNIInput.value.trim();
        const userPersonalEmail = correoPersonalInput.value.trim();
        const userPersonalPhone = celularPersonalInput.value.trim();

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfTokenMAIN
                },
                body: JSON.stringify({userEmail: userEmail, userDNI: userDNI, userPersonalEmail: userPersonalEmail, userPersonalPhone: userPersonalPhone}),
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
                
                    crearDatosUsuarioMessageError.textContent = erroresMsg.join(" ");
                    crearDatosPersonalesMessageError.textContent = erroresMsg.join(" ");
                    crearDatosUsuarioMessageError.classList.add("shown");
                    crearDatosPersonalesMessageError.classList.add("shown");
                
                    return;
                }

                crearDatosUsuarioMessageError.classList.remove("shown");
                crearDatosPersonalesMessageError.classList.remove("shown");
                guardarModal(idModal, idForm);	
            }
        } catch (error) {
            console.log(error);
            crearDatosUsuarioMessageError.textContent = 'Ocurrió un error al verificar datos duplicados del usuario a crear. Por favor, inténtelo de nuevo.';
            crearDatosPersonalesMessageError.textContent = 'Ocurrió un error al verificar datos duplicados del usuario a crear. Por favor, inténtelo de nuevo.';
            crearDatosUsuarioMessageError.classList.add("shown");
            crearDatosPersonalesMessageError.classList.add("shown");
        }
    } else {
        crearDatosUsuarioMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente";
        crearDatosPersonalesMessageError.textContent = "Todos los campos del formulario deben estar rellenados correctamente";
        crearDatosUsuarioMessageError.classList.add("shown");
        crearDatosPersonalesMessageError.classList.add("shown");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".section-tab.crear");
    const sections = document.querySelectorAll(".sectionContent.crear");
    
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

let mayorDeEdad = false;
let dniInput = document.getElementById('dniInput');
let nameInput = document.getElementById('nameInput');
let phoneInput = document.getElementById('phoneInput');
let idsOficioArrayInput = document.getElementById('idsOficioArrayInput');
let fechaNacimientoInput = document.getElementById('bornDateInput');
let multiMessageError = document.getElementById('multiMessageErrorNuevoTecnico');
let dateMessageError = document.getElementById('dateMessageError');

document.addEventListener("DOMContentLoaded", function() {
    if (fechaNacimientoInput) {
        // Establecer los atributos min y max una sola vez
        fechaNacimientoInput.setAttribute('min', minDateMAIN);
        fechaNacimientoInput.setAttribute('max', maxDateMAIN);

        fechaNacimientoInput.addEventListener('input', function() {
            validateRealTimeDate();
        });
    }

    // Función para validar la fecha
    function validateRealTimeDate() {
        const selectedDate = fechaNacimientoInput.value;
        const objSelectedDate = new Date(selectedDate);

        // Verificar si el campo de fecha está vacío
        if (!selectedDate) {
            dateMessageError.classList.remove('shown'); 
            return; // Salir de la función si el campo está vacío
        }

        if (selectedDate < minDateMAIN) {
            dateMessageError.textContent = `La fecha debe ser posterior al 1 de enero de ${minYearMAIN}.`; 
            dateMessageError.classList.add('shown'); // Mostrar mensaje de error
            return;
        }

        if (selectedDate >= maxDateMAIN) {
            dateMessageError.textContent = 'La fecha debe ser anterior a la fecha actual'; 
            dateMessageError.classList.add('shown'); // Mostrar mensaje de error
            return;
        }
        
        // Calcula la diferencia en milisegundos
        const differenceInMilliseconds = objMaxDateMAIN - objSelectedDate;
       
        // Calcula los años a partir de la diferencia en milisegundos
        const millisecondsPerYear = 1000 * 60 * 60 * 24 * 365.25; // Considera los años bisiestos
        const edad = Math.floor(differenceInMilliseconds / millisecondsPerYear);

        // Verificar si es mayor de edad
        if (edad < 18) {
            dateMessageError.textContent = 'El técnico debe ser mayor de edad.'; 
            dateMessageError.classList.add('shown'); 
            mayorDeEdad = false;
            return;
        }

        dateMessageError.classList.remove('shown');
        mayorDeEdad = true;
    }

    let oficiosSeleccionadosIds = [];

    // Registrar funciones inyectadas en multiSelectDropdown.js
    if (typeof window.registerExternFunction === "function") {
        window.registerExternFunction("idMultiSelectDropdownContainer_NuevoTecnico", "selectOptionOficio", function (optionValue) {
            const oficioId = parseInt(optionValue.trim().split('-')[0]);

            oficiosSeleccionadosIds = [...new Set([...oficiosSeleccionadosIds, oficioId])].sort((a, b) => a - b);
            idsOficioArrayInput.value = JSON.stringify(oficiosSeleccionadosIds);
        });

        window.registerExternFunction("idMultiSelectDropdownContainer_NuevoTecnico", "deleteOptionOficio", function (optionValue) {
            const oficioId = parseInt(optionValue.trim().split('-')[0]);

            oficiosSeleccionadosIds = oficiosSeleccionadosIds.filter(id => id !== oficioId);

            if (typeof idsOficioArrayInput !== "undefined" && idsOficioArrayInput !== null) {
                idsOficioArrayInput.value = oficiosSeleccionadosIds.length === 0 ? "" : JSON.stringify(oficiosSeleccionadosIds);
            }
        });
    } else {
        console.error("window.registerExternFunction no está definido.");
    }
});


function validateDate() {
    var selectedDate = fechaNacimientoInput.value;

    // Verificar si el campo de fecha está vacío
    if (!selectedDate) {
        dateMessageError.classList.remove('shown'); 
        return true; // Salir de la función si el campo está vacío
    }
    
    if (selectedDate < minDateMAIN) {
        return false;
    } else if (selectedDate > maxDateMAIN) {
        return false;
    } else {
        return true;
    }
}

function validateFormAgregarNuevoTecnico() {
    // Crear un objeto con las entradas y sus respectivos mensajes de error
    var fields = {
        dni: { value: dniInput.value},
        name: { value: nameInput.value},
        phone: { value: phoneInput.value},
        oficio: { value: idsOficioArrayInput.value},      
        fechaNacimiento: { value: fechaNacimientoInput.value},
    };

    // Verificar si todos los campos están llenos
    var isValid = true;
    for (var key in fields) {
        if (!fields[key].value) {
            isValid = false;
        }
    }

    isValid = (mayorDeEdad && isValid)? true:false;

    return isValid;
}

async function guardarModalAgregarNuevoTecnico(idModal, idForm) { 
    try {
        const idTecnico = dniInput.value.trim();
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url = `${baseUrlMAIN}/verificar-tecnico`;
        
        // Validar el formulario en el cliente
        if (!validateFormAgregarNuevoTecnico()) {
            multiMessageError.textContent = 'Debe completar todos los campos del formulario correctamente.';
            multiMessageError.classList.add('shown');
            return;
        }

        // Validar longitud de DNI y celular
        const isDniValid = validateInputLength(dniInput, 8);
        const isPhoneValid = validateInputLength(phoneInput, 9);

        if (!isDniValid || !isPhoneValid) {
            const errorMessages = [];
            if (!isDniValid) errorMessages.push('El campo DNI debe contener 8 caracteres.');
            if (!isPhoneValid) errorMessages.push('El campo Celular debe contener 9 dígitos.');
            multiMessageError.innerHTML = errorMessages.join('<br>'); // Combinar mensajes de error
            multiMessageError.classList.add('shown');
            return;
        }

        // Validar existencia de técnico o técnico inhabilitado con fetch
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ idTecnico })
        });

        if (!response.ok) {
            throw new Error('Error en la comunicación con el servidor.');
        }

        const data = await response.json();

        if (data.exists) {
            multiMessageError.textContent = data.message;
            multiMessageError.classList.add('shown');
            return; 
        }

        // Si todas las validaciones son correctas, enviar el formulario
        if (validateDate()) {
            multiMessageError.classList.remove('shown');
            guardarModal(idModal, idForm);
        }
    } catch (error) {
        // Manejo de errores
        console.error('Error al verificar existencia previa del técnico:', error);
        multiMessageError.textContent = 'Ocurrió un error al verificar la existencia previa del técnico. Por favor, inténtelo de nuevo.';
        multiMessageError.classList.add('shown');
    }
}
let today = new Date();
let minDate = '1900-01-01';
let maxDate = today.toISOString().split('T')[0];
let objMaxDate = new Date(maxDate); // Convierte maxDate a un objeto Date
let mayorDeEdad = false;
let idsOficioArrayInput = document.getElementById('idsOficioArrayInput');
let oficioAgregarTecnicoInput = document.getElementById('oficioInput');
    
document.addEventListener("DOMContentLoaded", function() {
    var dniInput = document.getElementById('dniInput');
    var phoneInput = document.getElementById('phoneInput');
    var dateInput = document.getElementById('bornDateInput');
    var dateMessageError = document.getElementById('dateMessageError');
    var multiMessageError = document.getElementById('multiMessageError');

    if (dateInput) {
        // Establecer los atributos min y max una sola vez
        dateInput.setAttribute('min', minDate);
        dateInput.setAttribute('max', maxDate);

        dateInput.addEventListener('input', function() {
            validateRealTimeDate();
        });
    }

     // Función para validar la fecha
    function validateRealTimeDate() {
        const selectedDate = dateInput.value;
        const objSelectedDate = new Date(selectedDate);

        // Verificar si el campo de fecha está vacío
        if (!selectedDate) {
            dateMessageError.classList.remove('shown'); 
            return; // Salir de la función si el campo está vacío
        }
        

       // Calcula la diferencia en milisegundos
        const differenceInMilliseconds = objMaxDate - objSelectedDate;
       
        // Calcula los años a partir de la diferencia en milisegundos
        const millisecondsPerYear = 1000 * 60 * 60 * 24 * 365.25; // Considera los años bisiestos
        const edad = Math.floor(differenceInMilliseconds / millisecondsPerYear);

        console.log(`Edad: ${edad} años`);


        // Verificar si es mayor de edad
        if (edad < 18) {
            console.log("El técnico debe ser mayor de edad.");
            dateMessageError.textContent = 'El técnico debe ser mayor de edad.'; 
            dateMessageError.classList.add('shown'); 
            mayorDeEdad = false;
            return;
        }

        mayorDeEdad = true;

        if (selectedDate < minDate) {
            dateMessageError.textContent = 'La fecha debe ser posterior al 1 de enero de 1900.'; 
            dateMessageError.classList.add('shown'); // Mostrar mensaje de error
        } else if (selectedDate > maxDate) {
            dateMessageError.textContent = 'La fecha debe ser anterior a la fecha actual'; 
            dateMessageError.classList.add('shown'); // Mostrar mensaje de error
        } else {
            dateMessageError.classList.remove('shown'); // Ocultar mensaje de error
        }
    }
});

let oficiosSeleccionadosIds = [];
let oficiosSeleccionados = []; // Array para guardar los oficios completos (id-nombre)

/* function selectOptionOficio(value, idInput, idOptions) {
    const oficioId = parseInt(value.split('-')[0]);
    
    if (oficioAgregarTecnicoInput.value.trim() == "") {
        var input = document.getElementById(idInput);
        var options = document.getElementById(idOptions);

        if (input) {
            input.value = value;
            options.classList.remove('show');
            // Almacenar el primer ID y valor completo
            oficiosSeleccionadosIds = [oficioId];
            oficiosSeleccionados = [value];
            console.log('IDs de oficios seleccionados:', oficiosSeleccionadosIds);
            // Actualizar el input oculto
            idsOficioArrayInput.value = JSON.stringify(oficiosSeleccionadosIds);
        } else {
            console.error('El elemento con id ' + idOptions + ' no se encontró en el DOM');
        }
        return;
    }

    var input = document.getElementById(idInput);
    var options = document.getElementById(idOptions);

    if (input) {
        // Agregar el nuevo ID y valor al array
        oficiosSeleccionadosIds.push(oficioId);
        oficiosSeleccionados.push(value);
        
        // Eliminar duplicados manteniendo la correspondencia entre arrays
        let unique = new Map();
        for(let i = 0; i < oficiosSeleccionadosIds.length; i++) {
            unique.set(oficiosSeleccionadosIds[i], oficiosSeleccionados[i]);
        }
        
        // Convertir el Map a arrays ordenados
        let sortedEntries = Array.from(unique.entries()).sort((a, b) => a[0] - b[0]);
        oficiosSeleccionadosIds = sortedEntries.map(entry => entry[0]);
        oficiosSeleccionados = sortedEntries.map(entry => entry[1]);
        
        // Actualizar el input con los valores ordenados
        input.value = oficiosSeleccionados.join(" | ");
        options.classList.remove('show');
        
        // Para debug
        console.log('IDs de oficios seleccionados:', oficiosSeleccionadosIds);
    } else {
        console.error('El elemento con id ' + idOptions + ' no se encontró en el DOM');
    }

    // Actualizar el input oculto
    idsOficioArrayInput.value = JSON.stringify(oficiosSeleccionadosIds);
    return;
} */

function selectOptionOficio(optionValue) {
    const oficioId = parseInt(optionValue.split('-')[0]);
    console.log(oficioId);
}

function cleanHiddenOficiosInput() {
    idsOficioArrayInput.value = "";
}

function validateDate() {
    var selectedDate = document.getElementById('bornDateInput').value;

    // Verificar si el campo de fecha está vacío
    if (!selectedDate) {
        dateMessageError.classList.remove('shown'); 
        return true; // Salir de la función si el campo está vacío
    }
    
    if (selectedDate < minDate) {
        return false;
    } else if (selectedDate > maxDate) {
        return false;
    } else {
        return true;
    }
}

function validateFormAgregarNuevoTecnico() {
    // Obtener referencias a los campos de entrada
    var dniInput = document.getElementById('dniInput');
    var nameInput = document.getElementById('nameInput');
    var phoneInput = document.getElementById('phoneInput');
    var oficioInput = document.getElementById('oficioInput');
    var fechaNacimientoInput = document.getElementById('bornDateInput');

    // Crear un objeto con las entradas y sus respectivos mensajes de error
    var fields = {
        dni: { value: dniInput.value, message: "DNI vacío" },
        name: { value: nameInput.value, message: "Nombre vacío" },
        phone: { value: phoneInput.value, message: "Celular vacío" },
        oficio: { value: oficioInput.value, message: "Oficio vacío" },
        fechaNacimiento: { value: fechaNacimientoInput.value, message: "Fecha de nacimiento vacío" },
    };

    // Verificar si todos los campos están llenos
    var isValid = true;
    for (var key in fields) {
        if (!fields[key].value) {
            //console.log(fields[key].message);
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

        // Validar existencia de técnico con fetch
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
            multiMessageError.textContent = `El técnico con DNI: ${idTecnico} ya ha sido registrado anteriormente.`;
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
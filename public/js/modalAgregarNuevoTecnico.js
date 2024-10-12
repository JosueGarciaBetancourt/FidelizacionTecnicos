let today = new Date();
let minDate = '1900-01-01';
let maxDate = today.toISOString().split('T')[0];
let objMaxDate = new Date(maxDate); // Convierte maxDate a un objeto Date
let mayorDeEdad = false;

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

function guardarModalAgregarNuevoTecnico(idModal, idForm, tecnicosDB) {
    itemArraySearched = returnItemDBValueWithRequestedID("idTecnico", dniInput.value, tecnicosDB);

    if (itemArraySearched) {
        multiMessageError.textContent = "El técnico con DNI: " + dniInput.value + " ya ha sido registrado anteriormente.";
        multiMessageError.classList.add("shown");
        return
    } 

    if (!validateFormAgregarNuevoTecnico()) {
        multiMessageError.textContent = 'Debe completar todos los campos del formulario correctamente.';
        multiMessageError.classList.add('shown');
        console.log("Debe completar todos los campos del formulario.");
        return; // Salir si la validación de campos falla
    }

    // Validar longitud de DNI y celular
    var isDniValid = validateInputLength(dniInput, 8);
    var isPhoneValid = validateInputLength(phoneInput, 9);

    if (!isDniValid && !isPhoneValid) { 
        multiMessageError.innerHTML  = `El campo DNI debe contener 8 caracteres. <br>
                                        El campo Celular debe contener 9 dígitos`; // Template literals → ALT GR + } = `
        multiMessageError.classList.add('shown');
        console.log("El campo DNI debe contener 8 caracteres.");
    } else if (!isDniValid) {
        multiMessageError.textContent = 'El campo DNI debe contener 8 caracteres.';
        multiMessageError.classList.add('shown');
        console.log("El campo DNI debe contener 8 caracteres.");
    } else if (!isPhoneValid) {
        multiMessageError.textContent = 'El campo Celular debe contener 9 dígitos.';
        multiMessageError.classList.add('shown');
        console.log("El campo Celular debe contener 9 dígitos.");
    }

    // Si ambas validaciones de longitud son correctas, enviar el formulario
    if (isDniValid && isPhoneValid && validateDate()) {
        multiMessageError.classList.remove('shown');
        guardarModal(idModal, idForm);
        /*document.getElementById(idForm).submit();
        closeModal(idModal);*/
    }
}

function openModal(modalId, origin=null) {
    if (origin) {
        // Establecer el valor de origen en el campo oculto
        document.getElementById('origin').value = origin;
    }

    var modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        setTimeout(function() {
            modal.style.opacity = 1; // Hacer el modal visible de forma gradual
            modal.querySelector('.modal-dialog').classList.add('open');
        }, 50); // Pequeño retraso para asegurar la transición CSS
        document.body.style.overflow = 'hidden'; // Evita el scroll de fondo cuando está abierto el modal
        
        // Recuperar el array de modales abiertos y agregar el nuevo
        let openModals = JSON.parse(localStorage.getItem('openModals')) || [];
        if (!openModals.includes(modalId)) {
            openModals.push(modalId);
            localStorage.setItem('openModals', JSON.stringify(openModals));
        }
    }
}

function openConfirmModal(modalId) {
    if (!modalId) return;

    console.log("Abriendo modal de confirmación con id: ", modalId);
    return new Promise((resolve) => {
        // Abrir modal (sin guardar en local storage)
        var modal = document.getElementById(modalId);
        modal.style.display = 'block';
        setTimeout(function() {
            modal.style.opacity = 1; // Hacer el modal visible de forma gradual
            modal.querySelector('.modal-dialog').classList.add('open');
        }, 50); // Pequeño retraso para asegurar la transición CSS
        document.body.style.overflow = 'hidden'; // Evita el scroll de fondo cuando está abierto el modal

        // Registrar eventos de confirmación
        window.yesConfirmAction = function () {
            closeModal(modalId);
            resolve(true); // Resuelve la promesa como true
        };

        window.noConfirmAction = function () {
            closeModal(modalId);    
            resolve(false); // Resuelve la promesa como false
        };
    });
}

function openConfirmActionOptionalComment(modalId) {
    if (!modalId) return;

    //console.log("Abriendo modal de confirmación con id: ", modalId);
    return new Promise((resolve) => {
        // Abrir modal
        const modal = document.getElementById(modalId);
        modal.style.display = 'block';
        setTimeout(() => {
            modal.style.opacity = 1; // Hacer el modal visible de forma gradual
            modal.querySelector('.modal-dialog').classList.add('open');
        }, 50); // Pequeño retraso para asegurar la transición CSS
        document.body.style.overflow = 'hidden'; // Evita el scroll de fondo cuando está abierto el modal

        // Registrar eventos de confirmación
        window.yesConfirmAction = function () {
            // Capturar el valor del comentario (si existe)
            const commentInput = modal.querySelector('#idComentarioInput');
            const comment = commentInput ? commentInput.value.trim() : null;
            closeModal(modalId);
            resolve({ answer: true, comment }); // Resuelve con un objeto
        };

        window.noConfirmAction = function () {
            closeModal(modalId);
            resolve({ answer: false, comment: null }); // Resuelve con un objeto
        };
    });
}

function openConfirmSolicitudCanjeModal(modalId) {
    if (!modalId) return;

    //console.log("Abriendo modal de confirmación con id: ", modalId);
    return new Promise((resolve) => {
        // Abrir modal
        const modal = document.getElementById(modalId);
        modal.style.display = 'block';
        setTimeout(() => {
            modal.style.opacity = 1; // Hacer el modal visible de forma gradual
            modal.querySelector('.modal-dialog').classList.add('open');
        }, 50); // Pequeño retraso para asegurar la transición CSS
        document.body.style.overflow = 'hidden'; // Evita el scroll de fondo cuando está abierto el modal

        // Registrar eventos de confirmación
        window.yesConfirmAction = function () {
            // Capturar el valor del comentario (si existe)
            const commentInput = modal.querySelector('#idComentarioInput');
            const comment = commentInput ? commentInput.value.trim() : null;
            if (comment) {
                closeModal(modalId);
                resolve({ answer: true, comment }); // Resuelve con un objeto
            } else {
                // No ingresa comentario
                document.getElementById('comentarioAprobRechaCanjeErrorMessage').classList.add('shown');
                console.log("NO PUSISTE UN COMENTARIO");
            }
        };

        window.noConfirmAction = function () {
            closeModal(modalId);
            resolve({ answer: false, comment: null }); // Resuelve con un objeto
        };
    });
}

function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    if (modal) {
        modal.querySelector('.modal-dialog').classList.remove('open');
        setTimeout(function() {
            modal.style.display = 'none';
        }, 300); // Espera 0.3 segundos (igual a la duración de la transición CSS)
        
         // Elimina el modal de la lista de modales abiertos
         let openModals = JSON.parse(localStorage.getItem('openModals')) || [];
         openModals = openModals.filter(id => id !== modalId);
         if (openModals.length > 0) {
            localStorage.setItem('openModals', JSON.stringify(openModals));
         } else {
            document.body.style.overflow = ''; //Permitir el scroll de fondo luego de cerrar todos los modales
            localStorage.removeItem('openModals');
         }
    }
}

/*MANEJAR INPUT FILE */
// Función para simular clic en el input file al hacer clic en el botón
function handleFileSelect() {
    const fileInput = document.getElementById('fileInput');
    fileInput.value = ''; // Limpia el valor del input antes de seleccionar el archivo
    fileInput.click();
}

var fileInput = document.getElementById('fileInput');
var fileArea = document.getElementById('fileArea');

if (fileInput) {
    // Event listener para cuando ya se seleccionó un archivo
    document.getElementById('fileInput').addEventListener('change', function() {
        var file = this.files[0]; // Obtener el archivo seleccionado
        if (file) {
            analizarXML(file);
        }
    });
} else {
    console.warn('El elemento fileInput no existe en el DOM actual.');
}

if (fileArea) {
    // Event listener para soltar sobre el área
    document.getElementById('fileArea').addEventListener('drop', function(event) {
        event.preventDefault();
        var file = event.dataTransfer.files[0]; // Obtener el archivo soltado
        if (file) {
            checkFileAccess(file).then((isAccessible) => {
                if (isAccessible) {
                    analizarXML(file);
                }
            }).catch((error) => {
                console.error('Error al revisar acceso a archivo:', error);
            });
        }
    });
} else {
    console.warn('El elemento fileArea no existe en el DOM actual.');
}

function allowDrop(event) {
    event.preventDefault(); 
    document.getElementById('fileArea').classList.add('drag-over');
}

function removeDrop(event) {
    event.preventDefault();
    document.getElementById('fileArea').classList.remove('drag-over');
}

function handleDrop(event) {
    event.preventDefault();
    document.getElementById('fileArea').classList.remove('drag-over');
}

// Función para verificar el acceso al archivo usando promesas
function checkFileAccess(file) {
    return new Promise((resolve, reject) => {
        // Comprobar el tipo de archivo
        if (file.type !== 'text/xml' && file.type !== '') {
            console.error('Tipo de archivo no permitido:', file.type);
            reject('Tipo de archivo no permitido');
        }

        // Intentar leer el archivo para verificar el acceso
        const reader = new FileReader();
        reader.readAsText(file);
        reader.onload = function() {
            console.log('Acceso al archivo permitido');
            resolve(true);
        };
        reader.onerror = function() {
            reject('Acceso al archivo denegado');
        };
    });
}
/*ENDING MANEJAR INPUT FILE */

document.addEventListener("DOMContentLoaded", function() {
    closeOptionsOnClickOutside();
    setOnlySelectInputFocusColor();

    let openModals = JSON.parse(localStorage.getItem('openModals')) || [];
    openModals.forEach(modalId => openModal(modalId));
});

function setOnlySelectInputFocusColor() {
    document.   addEventListener('click', function(event) {
        var elements = document.querySelectorAll('.onlySelectInput-container');
        elements.forEach(function(element) {
            var isClickInside = element.contains(event.target);
            if (!isClickInside) {
                element.classList.remove('activeFocus');
            } else {
                element.classList.add('activeFocus'); // Mantener el color de foco si está dentro
            }
        });
    });
}

function toggleOptions(idInput, idOptions) {
    var input = document.getElementById(idInput);
    var options = document.getElementById(idOptions);

    if (options) {
        if (input.value && !input.classList.contains("onlySelectInput")) {
            filterOptions(idInput, idOptions);
        } else {
            if (options.classList.contains('show')) {
                options.classList.remove('show');
            } else {
                options.classList.add('show');
            }
        }
    }
}

function toggleOptionsSelectNoCleanable(idOptions, idSpan) {
    var options = document.getElementById(idOptions);
    var span = document.getElementById(idSpan);

    if (options) {
        // Alternar la visibilidad de las opciones
        options.classList.toggle('show');
    }

    if (span) {
        span.textContent = span.textContent === "keyboard_arrow_down" ? "keyboard_arrow_up" : "keyboard_arrow_down";
    }
}

function filterOptions(idInput, idOptions) {
    var input, filter, ul, li, i, txtValue, hasVisibleOptions = false;
    input = document.getElementById(idInput);
    filter = input.value.toUpperCase();
    ul = document.getElementById(idOptions);
    li = ul.getElementsByTagName('li');
    
    for (i = 0; i < li.length;   i++) {
        txtValue = li[i].textContent || li[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
            hasVisibleOptions = true;
        } else {
            li[i].style.display = "none";
        }
    }

    if (hasVisibleOptions) {
        ul.classList.add('show');
    } else {
        ul.classList.remove('show');
    }
}

function selectOption(value, idInput, idOptions) {
    var input = document.getElementById(idInput);
    var options = document.getElementById(idOptions);

    if (input) {
        input.value = value;
        options.classList.remove('show'); // Ocultar las opciones
    } else {
        console.error('El elemento con id ' + idOptions + ' no se encontró en el DOM');
    }
}

function clearInput(idInput) {
    var input = document.getElementById(idInput); 
    if (input) {
        input.value = ''; // Limpia el valor del input
    } else {
        console.error('No se encontró un input siguiente para el contenedor ' + container + '.');
    }
}

function closeOptionsOnClickOutside() {
    // Encuentra todos los elementos select en el documento
    var selects = document.querySelectorAll('.input-select');
    
    // Función para manejar el clic fuera del select
    function handleClickOutside(event) {

        // Recorre todos los selects y verifica si el clic fue dentro de uno
        selects.forEach(function(select) {
            var options = select.querySelector('ul');
            var span = select.querySelector('.onlySelectInput-container .noCleanable');

            if (options) {
                if (!select.contains(event.target) && !options.contains(event.target)) {
                    options.classList.remove('show');
                    if (span) {
                        span.textContent = "keyboard_arrow_down";
                    }
                }
            }
        });
    }

    // Añadir el event listener de clic en el documento
    document.addEventListener('click', handleClickOutside);
}

function validateNumberRealTime(input) {
    // Elimina todos los caracteres que no sean dígitos como "e" ó "-"
    input.value = input.value.replace(/[^0-9]/g, '');
}

function validateNumberWithMaxLimitRealTime(input, maxLimit) {
    validateNumberRealTime(input);
    if (input.value > maxLimit) {
        input.value = 0;
    } 
}

function validateRealTimeInputLength(input, length) {
    if (input.value.length > length) {
        input.value = input.value.slice(0, length);
    }
}

function validateInputLength(input, length) {
    return input.value.length === length;
}

function guardarModal(idModal, idForm) {
    try {
        document.getElementById(idForm).submit();
        closeModal(idModal);
    } catch (error) {
        console.log(error.message);
        registrarErrorEnLaravel(error.message) 
    }
}

/*
function getAllLiText(idOptions) {
    // Obtener el elemento UL que contiene todas las opciones
    const ul = document.getElementById(idOptions);
    
    // Obtener todos los elementos LI dentro de la UL
    const liElements = ul.getElementsByTagName('li');
    
    // Extraer el texto de cada LI y almacenarlo en un array
    let tecnicos = [];
    for (let li of liElements) {
        tecnicos.push(li.textContent.trim());
    }
    
    return tecnicos;
}
Esta función está en dashboardScript.js
*/

function validateMinMaxRealTime(input, min, max) {
    // Obtener el valor actual del input
    const value = parseFloat(input.value);gi

    // Verificar si el valor es NaN o no está dentro del rango permitido
    if (isNaN(value)) {
        input.value = '';
        return;
    }

    // Ajustar el valor si está fuera del rango mínimo
    if (value < min) {
        input.value = min;
    }

    // Ajustar el valor si está fuera del rango máximo
    if (value > max) {
        input.value = max;
    }
}

function validateValueOnRealTime(input, idOptions, idMessageError, someHiddenIdInputsArray, 
                                otherInputsArray = null, itemsDB = null, searchField = null,
                                dbFieldsNameArray = null) {
    const value = input.value;

    if (!value) {
        return; 
    }

    const messageError = document.getElementById(idMessageError);
   
    // Recorrer el array y asignar valor vacío a cada input
    const clearHiddenInputs = () => {
        someHiddenIdInputsArray.forEach(idInput => {
            const inputElement = document.getElementById(idInput);
            if (inputElement) {
                inputElement.value = ""; // Asignar valor vacío
            }
        });
    };

    // Obtener todos los valores del item
    const allItems = getAllLiText(idOptions);

    // Comparar el valor ingresado con la lista de items
    const itemEncontrado = allItems.includes(value);

    // Dividir el valor en partes (id y nombre)
    const [id, nombre] = value.split(' | ');

    const clearInputs = () => {
        clearHiddenInputs();
        if (otherInputsArray) {
            otherInputsArray.forEach(idVisibleInput => {
                const visibleInputElement = document.getElementById(idVisibleInput);
                if (visibleInputElement) {
                    visibleInputElement.value = ""; 
                }
            });
        }
    };

    if (value === "") {
        messageError.classList.remove('shown');
        clearInputs();
    } else if (!itemEncontrado) {
        clearInputs();
        messageError.classList.add('shown'); 
    }
    else {
        messageError.classList.remove('shown');
        // Actualizar los inputs ocultos
        if (id) {
            document.getElementById(someHiddenIdInputsArray[0]).value = id;
            if (nombre && someHiddenIdInputsArray.length === 2) {
                document.getElementById(someHiddenIdInputsArray[1]).value = nombre;
            }
        }

        // Rellenar otros inputs visibles si se requiere
        if (otherInputsArray && itemsDB && searchField) {
            const searchValue = id;
            const itemArraySearched = returnItemDBValueWithRequestedID(searchField, searchValue, itemsDB);
            //console.log(itemArraySearched);

            if (itemArraySearched) {
                otherInputsArray.forEach((idVisibleInput, index) => {
                    const visibleInputElement = document.getElementById(idVisibleInput);
                    if (visibleInputElement) {
                        // Usar el índice para acceder al nombre del campo en dbFieldsNameArray
                        const dbField = dbFieldsNameArray[index];
                        visibleInputElement.value = itemArraySearched[dbField] || ""; 
                    }
                });
            }
        }
    }
}

function returnItemDBValueWithRequestedID(searchField, searchValue, itemsDB) {
    // Buscar el objeto en itemsDB que tenga el searchField con el valor searchValue
    for (const key in itemsDB) {
        if (itemsDB[key][searchField] === searchValue) {
            return itemsDB[key]; // Devolver el objeto encontrado
        }
    }

    return null; // Retornar null si no se encuentra el objeto
}

// string id debe tener el formato: CODIGO-001
function returnIDIntegerByStringID(idString) {
    console.log(idString);
    // Validar el formato y extraer el número después del guion
    const match = idString.match(/-\d+$/);
    if (!match) {
        throw new Error("El formato del ID no es válido. Debe contener un código seguido de un número (ejemplo: OFI-01).");
    }

    // Convertir la parte numérica en un entero
    const numberID = parseInt(match[0].replace('-', ''), 10);
    return numberID;
}

function validateValueOnRealTimeIDInteger(input, idOptions, idMessageError, someHiddenIdInputsArray, otherInputsArray = null, itemsDB = null, 
                                            searchField = null, dbFieldsNameArray = null) {
    const value = input.value;
    const messageError = document.getElementById(idMessageError);
    const clearHiddenInputs = () => {
        someHiddenIdInputsArray.forEach(idInput => {
            const inputElement = document.getElementById(idInput);
            if (inputElement) {
                inputElement.value = ""; // Asignar valor vacío
            }
        });
    };
    const clearInputs = () => {
        clearHiddenInputs();
        if (otherInputsArray) {
            otherInputsArray.forEach(idVisibleInput => {
                const visibleInputElement = document.getElementById(idVisibleInput);
                if (visibleInputElement) {
                    visibleInputElement.value = ""; 
                }
            });
        }
    };

    // VALIDACIONES

    if (!value || value === "") {
        clearInputs();
        messageError.classList.remove('shown');
        return; 
    }

    // Obtener todos los valores del item
    const allItems = getAllLiText(idOptions);

    // Comparar el valor ingresado con la lista de items
    const itemEncontrado = allItems.includes(value);
    
    if (!itemEncontrado) {
        clearInputs();
        messageError.classList.add('shown'); 
        return; 
    }

    messageError.classList.remove('shown');

    // Dividir el valor en partes (id y nombre)
    var [idString, nombre] = ["",""];

    if (value.includes("|")) {
        [idString, nombre] = value.split(' | ');
    } else {
        [idString, nombre] = [value,""];
    }

    const idInteger = returnIDIntegerByStringID(idString);   

    // Actualizar inputs ocultos
    if (idInteger && someHiddenIdInputsArray) {
        document.getElementById(someHiddenIdInputsArray[0]).value = idInteger;
        if (nombre && someHiddenIdInputsArray.length === 2) {
            document.getElementById(someHiddenIdInputsArray[1]).value = nombre;
        }
    }

    // Rellenar inputs visibles si se requiere
    if (otherInputsArray && itemsDB && searchField) {
        const searchValue = idInteger;
        const itemArraySearched = returnItemDBValueWithRequestedID(searchField, searchValue, itemsDB);

        if (itemArraySearched) {
            otherInputsArray.forEach((idVisibleInput, index) => {
                const visibleInputElement = document.getElementById(idVisibleInput);
                if (visibleInputElement) {
                    // Usar el índice para acceder al nombre del campo en dbFieldsNameArray
                    const dbField = dbFieldsNameArray[index];
                    visibleInputElement.value = itemArraySearched[dbField] || ""; 
                }
            });
        }
    }
}

function returnObjTecnicoById(idTecnico, tecnicosDB) {
    for (const key in tecnicosDB) {
        if (tecnicosDB[key]['idTecnico'] === idTecnico) {
            return tecnicosDB[key]; 
        }
    }
    return null; // Retornar null si no se encuentra el objeto
}

/*function getDiasTranscurridos(fechaEmision, fechaCargada) {
    // Asignar las fechas de los inputs
    var emision = new Date(fechaEmision); // Convierte la fecha de emisión a un objeto Date
    var carga = new Date(fechaCargada); // Convierte la fecha cargada a un objeto Date

    // Calcula la diferencia en milisegundos
    var diferenciaMilisegundos = Math.abs(carga - emision);

    // Convertir milisegundos a días
    var diasTranscurridos = diferenciaMilisegundos / (1000 * 60 * 60 * 24);

    return Math.floor(diasTranscurridos); // Redondea hacia abajo al número entero más cercano
}*/

function getDiasTranscurridos(fechaEmision, fechaCargada) {
    // Asegurar que las fechas sean objetos Date
    const emision = fechaEmision instanceof Date ? fechaEmision : new Date(fechaEmision);
    const carga = fechaCargada instanceof Date ? fechaCargada : new Date(fechaCargada);
    
    // Validar que las fechas sean válidas
    if (isNaN(emision.getTime()) || isNaN(carga.getTime())) {
        throw new Error('Fechas inválidas');
    }
    
    // Normalizar las fechas a UTC para evitar problemas con zonas horarias
    const emiUTC = Date.UTC(emision.getFullYear(), emision.getMonth(), emision.getDate());
    const cargUTC = Date.UTC(carga.getFullYear(), carga.getMonth(), carga.getDate());
    
    // Calcula la diferencia en días
    return Math.floor(Math.abs(cargUTC - emiUTC) / (1000 * 60 * 60 * 24));
}

function getDiasTranscurridosFechaHora(fechaEmision, fechaCargada) {
    // Asegurar que las fechas sean objetos Date
    const emision = fechaEmision instanceof Date ? fechaEmision : new Date(fechaEmision);
    const carga = fechaCargada instanceof Date ? fechaCargada : new Date(fechaCargada);

    // Validar que las fechas sean válidas
    if (isNaN(emision.getTime()) || isNaN(carga.getTime())) {
        throw new Error('Fechas inválidas');
    }

    // Obtiene la diferencia en milisegundos
    const diferenciaMilisegundos = Math.abs(carga.getTime() - emision.getTime());

    // Convierte la diferencia a días, considerando horas y fracciones
    return diferenciaMilisegundos / (1000 * 60 * 60 * 24);
}

